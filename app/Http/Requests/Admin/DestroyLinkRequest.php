<?php

namespace App\Http\Requests\Admin;

use App\Models\Links;
use Illuminate\Foundation\Http\FormRequest;

class DestroyLinkRequest extends FormRequest
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
     * Возвращает правила валидации удаления ссылки.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Links::getTableName().',id',
        ];
    }
}
