<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    /**
     * Разрешает отправку сообщения обратной связи посетителю сайта.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации формы обратной связи.
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
     * Возвращает пользовательские сообщения ошибок формы обратной связи.
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
