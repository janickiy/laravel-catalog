<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'activate' => 'nullable|array',
            'activate.*' => 'integer|exists:links,id',
            'action' => 'required|integer|in:0,1,2',
        ];
    }
}
