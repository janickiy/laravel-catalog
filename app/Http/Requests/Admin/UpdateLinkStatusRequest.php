<?php

namespace App\Http\Requests\Admin;

use App\Enums\LinkStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'action' => ['required', 'integer', Rule::in(LinkStatus::values())],
        ];
    }
}
