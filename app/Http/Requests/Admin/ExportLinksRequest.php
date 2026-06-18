<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportLinksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'catalog_id' => [
                'nullable',
                'integer',
                Rule::when((int) $this->input('catalog_id') > 0, ['exists:catalog,id']),
            ],
            'export_type' => 'required|in:text,excel',
            'compress' => 'required|in:none,zip',
        ];
    }
}
