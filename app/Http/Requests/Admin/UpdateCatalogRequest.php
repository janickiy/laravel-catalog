<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCatalogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:catalog,id',
            'name' => 'required',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'image' => 'image|mimes:jpeg,jpg,gif,png|max:2048|nullable',
            'parent_id' => 'integer',
            'pic' => 'nullable|string',
        ];
    }
}
