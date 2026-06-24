<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use App\Models\Links;
use App\Rules\DomainOrUrl;
use Illuminate\Validation\Rule;

class UpdateLinkRequest extends StoreLinkRequest
{
    /**
     * Returns the validation rules for link updates.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Links::getTableName().',id',
            'name' => 'required',
            'url' => ['required', new DomainOrUrl(__('interface.validation.url')), 'unique:'.Links::getTableName().',url,'.$this->input('id')],
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
