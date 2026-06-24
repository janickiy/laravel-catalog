<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use App\Models\Links;
use App\Rules\DomainOrUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLinkRequest extends FormRequest
{
    /**
     * Authorizes link creation for an authenticated administrator.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for link creation.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'url' => ['required', new DomainOrUrl(__('interface.validation.url')), 'unique:'.Links::getTableName()],
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
     * Returns custom error messages for link creation.
     */
    public function messages(): array
    {
        return [
            'required' => __('interface.validation.required'),
            'url.unique' => __('interface.validation.url_unique'),
        ];
    }
}
