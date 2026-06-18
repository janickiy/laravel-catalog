<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class DestroyAdminRequest extends FormRequest
{
    /**
     * Переносит id из маршрута в данные запроса перед валидацией.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    /**
     * Разрешает выполнение запроса авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации удаления администратора.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Admin::getTableName().',id',
        ];
    }
}
