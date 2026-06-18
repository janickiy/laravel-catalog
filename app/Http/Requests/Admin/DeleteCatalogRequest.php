<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCatalogRequest extends FormRequest
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
     * Возвращает правила валидации удаления раздела каталога.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Catalog::getTableName().',id',
        ];
    }
}
