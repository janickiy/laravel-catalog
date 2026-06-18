<?php

namespace App\Http\Requests\Admin;

use App\Enums\LinkStatus;
use App\Models\Links;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLinkStatusRequest extends FormRequest
{
    /**
     * Разрешает массовое изменение статусов авторизованному администратору.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации массового изменения статусов ссылок.
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
