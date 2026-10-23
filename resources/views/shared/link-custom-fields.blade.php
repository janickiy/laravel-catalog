@php
    $admin = $admin ?? false;
    $values = $values ?? [];
    $visibleFields = collect($customFields ?? [])->filter(fn ($field) => $admin || $field->is_active);
@endphp
@if($visibleFields->isNotEmpty())
    <fieldset class="link-custom-fields {{ $admin ? 'border-0 p-0 m-0' : '' }}">
        <legend class="{{ $admin ? 'fs-6 fw-semibold mb-3' : 'link-custom-fields__heading' }}">{{ __('submission.additional_details') }}</legend>
        @foreach($visibleFields as $field)
            @php
                $fieldId = 'custom-field-' . $field->id;
                $fieldName = 'custom_fields[' . $field->id . ']';
                $errorKey = 'custom_fields.' . $field->id;
                $fieldValue = old($errorKey, $values[$field->id] ?? null);
                $fieldValue = is_scalar($fieldValue) ? $fieldValue : '';
                $required = $field->is_active && $field->is_required;
                $hasError = $errors->has($errorKey);
                $descriptionIds = trim(($field->help_text ? $fieldId . '-help ' : '') . ($hasError ? $fieldId . '-error' : ''));
                $inputClass = ($admin && $field->type === 'select' ? 'form-select' : 'form-control') . ($hasError ? ' is-invalid' : '');
            @endphp
            <div class="{{ $admin ? 'mb-3' : 'form-group' }}">
                @if($field->type === 'checkbox')
                    <input type="hidden" name="{{ $fieldName }}" value="0">
                    <div class="{{ $admin ? 'form-check' : 'link-custom-fields__check' }}">
                        <input type="checkbox" id="{{ $fieldId }}" name="{{ $fieldName }}" value="1" class="{{ $admin ? 'form-check-input' : '' }}{{ $hasError ? ' is-invalid' : '' }}" @checked(in_array($fieldValue, [true, 1, '1', 'true', 'on'], true)) @required($required) aria-required="{{ $required ? 'true' : 'false' }}" aria-invalid="{{ $hasError ? 'true' : 'false' }}" @if($descriptionIds) aria-describedby="{{ $descriptionIds }}" @endif>
                        <label for="{{ $fieldId }}" class="{{ $admin ? 'form-check-label' : '' }}">{{ $field->label }}@if($required)<span aria-hidden="true"> *</span>@endif</label>
                    </div>
                @else
                    <label for="{{ $fieldId }}" class="form-label {{ $admin ? 'fw-semibold' : '' }}">{{ $field->label }}@if($required)<span aria-hidden="true"> *</span>@endif</label>
                    @if($field->type === 'textarea')
                        <textarea id="{{ $fieldId }}" name="{{ $fieldName }}" rows="3" class="{{ $inputClass }}" @required($required) aria-required="{{ $required ? 'true' : 'false' }}" aria-invalid="{{ $hasError ? 'true' : 'false' }}" @if($descriptionIds) aria-describedby="{{ $descriptionIds }}" @endif>{{ $fieldValue }}</textarea>
                    @elseif($field->type === 'select')
                        <select id="{{ $fieldId }}" name="{{ $fieldName }}" class="{{ $inputClass }}" @required($required) aria-required="{{ $required ? 'true' : 'false' }}" aria-invalid="{{ $hasError ? 'true' : 'false' }}" @if($descriptionIds) aria-describedby="{{ $descriptionIds }}" @endif>
                            <option value="">{{ __('submission.choose_option') }}</option>
                            @foreach($field->options ?? [] as $option)
                                <option value="{{ $option }}" @selected((string) $fieldValue === (string) $option)>{{ $option }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="{{ in_array($field->type, ['url', 'number'], true) ? $field->type : 'text' }}" id="{{ $fieldId }}" name="{{ $fieldName }}" value="{{ $fieldValue }}" class="{{ $inputClass }}" @if($field->type === 'number') step="any" @endif @required($required) aria-required="{{ $required ? 'true' : 'false' }}" aria-invalid="{{ $hasError ? 'true' : 'false' }}" @if($descriptionIds) aria-describedby="{{ $descriptionIds }}" @endif>
                    @endif
                @endif
                @if($admin && !$field->is_active)<p class="small text-secondary mt-1 mb-0">{{ __('submission.field_inactive') }}</p>@endif
                @if($field->help_text)<p id="{{ $fieldId }}-help" class="{{ $admin ? 'form-text mb-0' : 'link-custom-fields__help' }}">{{ $field->help_text }}</p>@endif
                @if($hasError)<div id="{{ $fieldId }}-error" class="{{ $admin ? 'invalid-feedback d-block' : 'form-error' }}">{{ $errors->first($errorKey) }}</div>@endif
            </div>
        @endforeach
    </fieldset>
@endif
@if($errors->has('custom_fields'))<div class="{{ $admin ? 'invalid-feedback d-block' : 'form-error' }}">{{ $errors->first('custom_fields') }}</div>@endif
