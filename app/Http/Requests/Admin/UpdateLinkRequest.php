<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use App\Models\Links;
use Illuminate\Validation\Rule;

class UpdateLinkRequest extends StoreLinkRequest
{
    /**
     * Возвращает правила валидации обновления ссылки.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Links::getTableName().',id',
            'name' => 'required',
            'url' => 'required|url|unique:'.Links::getTableName().',url,'.$this->input('id'),
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
}
