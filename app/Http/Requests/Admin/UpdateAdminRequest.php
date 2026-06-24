<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Authorizes administrator updates for an authenticated administrator.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for administrator updates.
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
