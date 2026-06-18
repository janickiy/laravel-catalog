<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCatalogRequest extends FormRequest
{
    /**
     * Разрешает обновление раздела каталога авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации обновления раздела каталога.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Catalog::getTableName().',id',
            'name' => 'required',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'image' => 'image|mimes:jpeg,jpg,gif,png|max:2048|nullable',
            'parent_id' => [
                'nullable',
                'integer',
                Rule::when((int) $this->input('parent_id') > 0, ['exists:'.Catalog::getTableName().',id']),
            ],
            'pic' => 'nullable|string',
        ];
    }
}
