<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ImportLinksRequest extends FormRequest
{
    /**
     * Разрешает выполнение импорта авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации файла импорта ссылок.
     */
    public function rules(): array
    {
        return [
            'file' => 'nullable|file|mimes:csv,xlsx,xls,txt',
            'archive' => 'nullable|file|mimes:zip',
        ];
    }

    /**
     * Проверяет, что выбран хотя бы один источник импорта.
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
     * Возвращает пользовательские сообщения ошибок импорта.
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
