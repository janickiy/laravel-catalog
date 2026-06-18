<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    /**
     * Разрешает создание администратора авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации создания администратора.
     */
    public function rules(): array
    {
        return [
            'login' => 'required|unique:'.Admin::getTableName().'|max:255',
            'name' => 'required',
            'password' => 'required|min:6',
            'password_again' => 'required|min:6|same:password',
        ];
    }
}
