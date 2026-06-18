<?php

namespace App\Http\Requests\Frontend;

use App\Models\Catalog;
use App\Models\Links;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLinkRequest extends FormRequest
{
    /**
     * Разрешает отправку заявки на добавление сайта посетителю.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Возвращает правила валидации заявки на добавление сайта.
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'url' => 'required|url|unique:'.Links::getTableName(),
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
     * Возвращает пользовательские сообщения ошибок заявки на добавление сайта.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Не указано название!',
            'url.required' => 'Не указан URL адрес сайта!',
            'url.url' => 'Не верно указан URL адрес сайта!',
            'url.unique' => 'Этот сайт уже есть в каталоге!',
            'email.email' => 'Не верно указан email!',
            'description.required' => 'Не указано описание!',
            'description.min' => 'Количество символов в описание не должно быть меньше :min',
            'description.max' => 'Количество символов в описание не должно быть больше :max',
            'full_description.required' => 'Не указано полное описание!',
            'full_description.min' => 'Количество символов в полном описание не должно быть меньше :min',
            'full_description.max' => 'Количество символов в полном описание не должно быть больше :max',
            'catalog_id.required' => 'Выберите раздел!',
            'captcha.required' => 'Не указан защитный код!',
            'agree.required' => 'Вы должны принять правила каталога',
        ];
    }
}
