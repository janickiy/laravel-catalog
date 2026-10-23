<?php

namespace App\Http\Requests\Admin;

use App\Models\LinkCustomField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveLinkCustomFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    protected function prepareForValidation(): void
    {
        $text = $this->input('options_text');
        $options = is_string($text)
            ? array_values(array_filter(array_map('trim', preg_split('/\R/u', $text) ?: []), fn (string $option): bool => $option !== ''))
            : [];

        $this->merge([
            'options' => $options,
            'is_required' => $this->input('is_required', false),
            'is_active' => $this->input('is_active', false),
            'is_public' => $this->input('is_public', false),
        ]);
    }

    public function rules(): array
    {
        $field = $this->route('customField');
        $types = $field instanceof LinkCustomField ? [$field->type] : LinkCustomField::TYPES;

        return [
            'label' => ['required', 'string', 'max:120'],
            'type' => ['required', Rule::in($types)],
            'help_text' => ['nullable', 'string', 'max:255'],
            'options_text' => ['nullable', 'string', 'max:5000'],
            'options' => [Rule::requiredIf($this->input('type') === 'select'), 'array', 'max:30'],
            'options.*' => ['required', 'string', 'max:120', 'distinct'],
            'is_required' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'is_public' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ];
    }

    public function attributes(): array
    {
        return [
            'label' => __('custom_fields.label'),
            'type' => __('custom_fields.type'),
            'help_text' => __('custom_fields.help_text'),
            'options' => __('custom_fields.options'),
            'options_text' => __('custom_fields.options'),
            'options.*' => __('custom_fields.option'),
            'is_required' => __('custom_fields.is_required'),
            'is_active' => __('custom_fields.is_active'),
            'is_public' => __('custom_fields.is_public'),
            'sort_order' => __('custom_fields.sort_order'),
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => $this->route('customField')
                ? __('custom_fields.type_immutable')
                : __('custom_fields.invalid_type'),
            'options.required' => __('custom_fields.options_required'),
            'options.max' => __('custom_fields.options_limit'),
            'options.*.max' => __('custom_fields.option_limit'),
            'options.*.distinct' => __('custom_fields.options_distinct'),
        ];
    }

    public function fieldAttributes(): array
    {
        $data = $this->safe()->only([
            'label', 'type', 'help_text', 'options', 'is_required', 'is_active', 'is_public', 'sort_order',
        ]);
        $data['options'] = $data['type'] === 'select' ? $data['options'] : null;

        return $data;
    }
}
