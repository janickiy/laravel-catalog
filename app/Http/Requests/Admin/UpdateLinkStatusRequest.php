<?php

namespace App\Http\Requests\Admin;

use App\Enums\LinkStatus;
use App\Models\Links;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLinkStatusRequest extends FormRequest
{
    /**
     * Authorizes bulk status changes for an authenticated administrator.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for bulk link status changes.
     */
    public function rules(): array
    {
        return [
            'activate' => 'nullable|array',
            'activate.*' => 'integer|exists:'.Links::getTableName().',id',
            'action' => ['required', 'integer', Rule::in(LinkStatus::values())],
        ];
    }
}
