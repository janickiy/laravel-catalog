<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExportLinksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'catalog_id' => 'nullable|integer',
            'export_type' => 'required|in:text,excel',
            'compress' => 'required|in:none,zip',
        ];
    }
}
