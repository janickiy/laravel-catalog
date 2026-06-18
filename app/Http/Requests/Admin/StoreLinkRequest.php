<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use App\Models\Links;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLinkRequest extends FormRequest
{
    /**
     * Разрешает создание ссылки авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации создания ссылки.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'url' => 'required|url|unique:'.Links::getTableName(),
            'city' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'description' => 'required',
            'keywords' => 'nullable|string',
            'full_description' => 'required',
            'catalog_id' => [
                'required',
                'integer',
                Rule::when((int) $this->input('catalog_id') > 0, ['exists:'.Catalog::getTableName().',id']),
            ],
        ];
    }

    /**
     * Возвращает пользовательские сообщения ошибок создания ссылки.
     */
    public function messages(): array
    {
        return [
            'required' => 'Это поле обязательно для заполнения!',
            'url' => 'URL адрес введен неверно',
            'url.unique' => 'Сайт с таким URL уже есть в каталоге!',
        ];
    }
}
