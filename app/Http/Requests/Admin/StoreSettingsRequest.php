<?php

namespace App\Http\Requests\Admin;

use App\Models\Settings;
use Illuminate\Foundation\Http\FormRequest;

class StoreSettingsRequest extends FormRequest
{
    /**
     * Разрешает создание настройки авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации создания настройки.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:'.Settings::getTableName(),
            'value' => 'required',
            'description' => 'nullable|string',
        ];
    }
}
