<?php

namespace App\Services\Links;

use App\Models\LinkCustomField;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;

class CustomFieldService
{
    public function fields(bool $admin = false): Collection
    {
        return LinkCustomField::query()
            ->when(! $admin, fn ($query) => $query->where('is_active', true))
            ->ordered()->get();
    }

    public function rules(bool $admin = false): array
    {
        $fields = $this->fields($admin);
        $rules = ['custom_fields' => ['nullable', $fields->isEmpty()
            ? 'array' : 'array:'.implode(',', $fields->modelKeys())]];

        if ($fields->isEmpty()) {
            $rules['custom_fields'][] = 'size:0';
        }

        foreach ($fields as $field) {
            $required = $field->is_active && $field->is_required;
            $typeRules = match ($field->type) {
                'textarea' => ['string', 'max:2000'],
                'url' => ['string', 'url:http,https', 'max:2048'],
                'number' => ['numeric', 'between:-1000000000000,1000000000000'],
                'select' => ['string', Rule::in($field->options ?? [])],
                'checkbox' => $required ? ['accepted'] : ['boolean'],
                default => ['string', 'max:255'],
            };
            $rules['custom_fields.'.$field->id] = [$required ? 'required' : 'nullable', ...$typeRules];
        }

        return $rules;
    }

    public function attributes(bool $admin = false): array
    {
        return $this->fields($admin)->mapWithKeys(
            fn (LinkCustomField $field) => ['custom_fields.'.$field->id => $field->label]
        )->all();
    }

    /** Only configured fields are accepted; hidden answers survive an admin edit. */
    public function normalize(array $values, array $existing = [], bool $admin = false): array
    {
        foreach ($this->fields($admin) as $field) {
            $value = $values[$field->id] ?? null;
            $existing[$field->id] = $field->type === 'checkbox'
                ? filter_var($value, FILTER_VALIDATE_BOOLEAN)
                : ($value === null || $value === '' ? null : (string) $value);
        }

        return $existing;
    }
}
