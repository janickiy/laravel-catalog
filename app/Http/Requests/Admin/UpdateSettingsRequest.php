<?php

namespace App\Http\Requests\Admin;

use App\Models\Settings;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Разрешает обновление настройки авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации обновления настройки.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Settings::getTableName().',id',
            'name' => 'required|max:255|unique:'.Settings::getTableName().',name,'.$this->input('id').',id',
            'value' => 'required',
            'description' => 'nullable|string',
        ];
    }
}
