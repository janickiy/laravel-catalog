<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ImportLinksRequest extends FormRequest
{
    /**
     * Authorizes import execution for an authenticated administrator.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for the link import file.
     */
    public function rules(): array
    {
        return [
            'file' => 'nullable|file|mimes:csv,xlsx,xls,txt',
            'archive' => 'nullable|file|mimes:zip',
        ];
    }

    /**
     * Checks that at least one import source was selected.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->hasFile('file') && ! $this->hasFile('archive')) {
                $validator->errors()->add('file', __('interface.validation.import_required'));
            }
        });
    }

    /**
     * Returns custom import error messages.
     */
    public function messages(): array
    {
        return [
            'required' => __('interface.validation.required'),
            'file.file' => __('interface.validation.required'),
            'file.mimes' => __('interface.validation.import_mimes'),
            'archive.file' => __('interface.validation.required'),
            'archive.mimes' => __('interface.validation.import_archive_mimes'),
        ];
    }
}
