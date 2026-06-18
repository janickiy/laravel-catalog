<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCatalogRequest extends FormRequest
{
    /**
     * Разрешает создание раздела каталога авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации создания раздела каталога.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'image' => 'image|mimes:jpeg,jpg,gif,png|max:2048|nullable',
            'parent_id' => [
                'nullable',
                'integer',
                Rule::when((int) $this->input('parent_id') > 0, ['exists:'.Catalog::getTableName().',id']),
            ],
        ];
    }
}
