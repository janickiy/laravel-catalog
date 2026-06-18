<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
            'file' => 'required|mimes:csv,xlsx,xls,txt',
        ];
    }

    /**
     * Возвращает пользовательские сообщения ошибок импорта.
     */
    public function messages(): array
    {
        return [
            'required' => 'Это поле обязательно для заполнения!',
            'mimes' => 'Разрешено загружать файлы: csv,xlsx,xls,txt!',
        ];
    }
}
