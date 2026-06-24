<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    /**
     * Authorizes feedback message submission by a site visitor.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns the validation rules for the feedback form.
     */
    public function rules(): array
    {
        return [
            'message' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'captcha' => 'required|captcha',
        ];
    }

    /**
     * Returns custom error messages for the feedback form.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('interface.validation.feedback_name_required'),
            'email.required' => __('interface.validation.email_required'),
            'email.email' => __('interface.validation.email'),
            'message.required' => __('interface.validation.feedback_message_required'),
            'captcha.required' => __('interface.validation.captcha_required'),
        ];
    }
}
