<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCatalogRequest extends FormRequest
{
    /**
     * Authorizes catalog category creation for an authenticated administrator.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for catalog category creation.
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
