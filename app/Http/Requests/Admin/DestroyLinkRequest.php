<?php

namespace App\Http\Requests\Admin;

use App\Models\Links;
use Illuminate\Foundation\Http\FormRequest;

class DestroyLinkRequest extends FormRequest
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
     * Returns the validation rules for link deletion.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Links::getTableName().',id',
        ];
    }
}
