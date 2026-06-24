<?php

namespace App\Http\Requests\Frontend;

use App\Models\Catalog;
use App\Models\Links;
use App\Rules\DomainOrUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLinkRequest extends FormRequest
{
    /**
     * Authorizes website submissions from visitors.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for the website submission form.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'url' => ['required', new DomainOrUrl(__('interface.validation.frontend_url')), 'unique:'.Links::getTableName()],
            'description' => 'required|min:100|max:300',
            'full_description' => 'required|min:200|max:2000',
            'email' => 'nullable|email',
            'city' => 'nullable|string',
            'phone' => 'nullable|string',
            'keywords' => 'nullable|string',
            'catalog_id' => [
                'required',
                'integer',
                Rule::when((int) $this->input('catalog_id') > 0, ['exists:'.Catalog::getTableName().',id']),
            ],
            'captcha' => 'required|captcha',
            'agree' => 'required',
        ];
    }

    /**
     * Returns custom error messages for the website submission form.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('interface.validation.name_required'),
            'url.required' => __('interface.validation.frontend_url_required'),
            'url.unique' => __('interface.validation.frontend_url_unique'),
            'email.email' => __('interface.validation.email'),
            'description.required' => __('interface.validation.description_required'),
            'description.min' => __('interface.validation.description_min'),
            'description.max' => __('interface.validation.description_max'),
            'full_description.required' => __('interface.validation.full_description_required'),
            'full_description.min' => __('interface.validation.full_description_min'),
            'full_description.max' => __('interface.validation.full_description_max'),
            'catalog_id.required' => __('interface.validation.catalog_required'),
            'captcha.required' => __('interface.validation.captcha_required'),
            'agree.required' => __('interface.validation.agree_required'),
        ];
    }
}
