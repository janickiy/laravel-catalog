<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Разрешает обновление администратора авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации обновления администратора.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Admin::getTableName().',id',
            'login' => 'required|max:255|unique:'.Admin::getTableName().',login,'.$this->input('id'),
            'name' => 'required',
            'password' => 'min:6|nullable',
            'password_again' => 'min:6|same:password|nullable',
        ];
    }
}
