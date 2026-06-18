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
            'name.required' => 'Укажите Ваше имя!',
            'email.required' => 'Не указан Email!',
            'email.email' => 'Не верно указан Email!',
            'message.required' => 'Введите сообщение',
            'captcha.required' => 'Не указан защитный код!',
        ];
    }
}
