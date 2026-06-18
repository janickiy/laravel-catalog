<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkRequest extends StoreLinkRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:links,id',
            'name' => 'required',
            'url' => 'required|url|unique:links,url,' . $this->input('id'),
            'city' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'description' => 'required',
            'keywords' => 'nullable|string',
            'full_description' => 'required',
            'catalog_id' => 'required|integer',
        ];
    }
}
