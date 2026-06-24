<?php

namespace App\Http\Requests\Admin;

use App\Models\Settings;
use Illuminate\Foundation\Http\FormRequest;

class DestroySettingsRequest extends FormRequest
{
    /**
     * Moves the ID from the route into request data before validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }

    /**
     * Authorizes the request for an authenticated administrator.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for setting deletion.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Settings::getTableName().',id',
        ];
    }
}
