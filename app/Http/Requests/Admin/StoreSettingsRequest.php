<?php

namespace App\Http\Requests\Admin;

use App\Models\Settings;
use Illuminate\Foundation\Http\FormRequest;

class StoreSettingsRequest extends FormRequest
{
    /**
     * Authorizes setting creation for an authenticated administrator.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for setting creation.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:'.Settings::getTableName(),
            'value' => 'required',
            'description' => 'nullable|string',
        ];
    }
}
