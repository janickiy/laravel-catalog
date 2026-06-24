<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportLinksRequest extends FormRequest
{
    /**
     * Authorizes export execution for an authenticated administrator.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for link export parameters.
     */
    public function rules(): array
    {
        return [
            'catalog_id' => [
                'nullable',
                'integer',
                Rule::when((int) $this->input('catalog_id') > 0, ['exists:'.Catalog::getTableName().',id']),
            ],
            'export_type' => 'required|in:text,excel',
            'compress' => 'required|in:none,zip',
        ];
    }
}
