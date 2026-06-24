<?php

namespace App\Http\Requests\Admin;

use App\Models\Catalog;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCatalogRequest extends FormRequest
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
     * Returns the validation rules for catalog category deletion.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:'.Catalog::getTableName().',id',
        ];
    }
}
