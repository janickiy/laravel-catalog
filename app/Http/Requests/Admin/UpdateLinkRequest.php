<?php

namespace App\Http\Requests\Admin;

use App\Models\Links;
use App\Rules\DomainOrUrl;
use Illuminate\Validation\Rule;

class UpdateLinkRequest extends StoreLinkRequest
{
    /**
     * Returns the validation rules for link updates.
     */
    public function rules(): array
    {
        return array_replace(parent::rules(), [
            'id' => 'required|integer|exists:'.Links::getTableName().',id',
            'url' => ['required', new DomainOrUrl(__('interface.validation.url')), Rule::unique(Links::getTableName(), 'url')->ignore((int) $this->input('id'))],
        ]);
    }
}
