<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    /**
     * Authorizes administrator creation for an authenticated administrator.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for administrator creation.
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
