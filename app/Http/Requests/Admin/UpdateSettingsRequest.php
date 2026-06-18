<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:settings,id',
            'name' => 'required|max:255|unique:settings,name,' . $this->input('id') . ',id',
            'value' => 'required',
            'description' => 'nullable|string',
        ];
    }
}
