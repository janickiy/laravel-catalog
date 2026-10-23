@extends('cp.app')

@section('title', $title)

@section('content')
    <form method="post" action="{{ $field->exists ? route('cp.custom-fields.update', $field) : route('cp.custom-fields.store') }}" class="card shadow-sm" style="max-width: 900px">
        @csrf
        @if($field->exists) @method('PUT') @endif
        <div class="card-body">
            <p class="text-secondary mb-4">{{ __('custom_fields.intro') }}</p>
            <div class="row g-4">
                <div class="col-12 col-md-8">
                    <label class="form-label fw-semibold" for="label">{{ __('custom_fields.label') }} *</label>
                    <input class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label', $field->label) }}" maxlength="120" required @error('label') aria-invalid="true" aria-describedby="label-error" @enderror>
                    @error('label')<div class="invalid-feedback" id="label-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="type">{{ __('custom_fields.type') }} *</label>
                    @if($field->exists)
                        <input type="hidden" name="type" value="{{ $field->type }}">
                        <input class="form-control" id="type" value="{{ __('custom_fields.types.' . $field->type) }}" readonly aria-describedby="type-help">
                        <div class="form-text" id="type-help">{{ __('custom_fields.type_immutable') }}</div>
                    @else
                        <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required>
                            @foreach(\App\Models\LinkCustomField::TYPES as $type)
                                <option value="{{ $type }}" @selected(old('type', $field->type) === $type)>{{ __('custom_fields.types.' . $type) }}</option>
                            @endforeach
                        </select>
                    @endif
                    @error('type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold" for="help_text">{{ __('custom_fields.help_text') }}</label>
                    <input class="form-control @error('help_text') is-invalid @enderror" id="help_text" name="help_text" value="{{ old('help_text', $field->help_text) }}" maxlength="255">
                    @error('help_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12" data-options-panel>
                    <label class="form-label fw-semibold" for="options_text">{{ __('custom_fields.options') }}</label>
                    <textarea class="form-control @if($errors->has('options') || $errors->has('options.*') || $errors->has('options_text')) is-invalid @endif" id="options_text" name="options_text" rows="5" maxlength="5000" aria-describedby="options-help">{{ old('options_text', implode("\n", $field->options ?? [])) }}</textarea>
                    <div class="form-text" id="options-help">{{ __('custom_fields.options_hint') }}</div>
                    @foreach(array_unique(array_merge($errors->get('options'), $errors->get('options_text'), \Illuminate\Support\Arr::flatten($errors->get('options.*')))) as $error)
                        <div class="invalid-feedback d-block">{{ $error }}</div>
                    @endforeach
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-semibold" for="sort_order">{{ __('custom_fields.sort_order') }}</label>
                    <input class="form-control @error('sort_order') is-invalid @enderror" type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $field->sort_order) }}" min="0" max="9999" step="1" required>
                    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    @foreach(['is_active', 'is_required', 'is_public'] as $flag)
                        <div class="form-check mb-3">
                            <input type="hidden" name="{{ $flag }}" value="0">
                            <input class="form-check-input @error($flag) is-invalid @enderror" type="checkbox" id="{{ $flag }}" name="{{ $flag }}" value="1" @checked(old($flag, $field->{$flag})) aria-describedby="{{ $flag }}-help">
                            <label class="form-check-label fw-semibold" for="{{ $flag }}">{{ __('custom_fields.' . $flag) }}</label>
                            <div class="form-text" id="{{ $flag }}-help">{{ __('custom_fields.' . $flag . '_hint') }}</div>
                            @error($flag)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card-footer d-flex flex-wrap justify-content-between gap-2">
            <a class="btn btn-outline-secondary" href="{{ route('cp.custom-fields.index') }}">{{ __('custom_fields.back') }}</a>
            <button class="btn btn-primary" type="submit">{{ __('custom_fields.save') }}</button>
        </div>
    </form>
@endsection

@section('js')
    <script>
        (() => {
            const type = document.querySelector('[name="type"]');
            const panel = document.querySelector('[data-options-panel]');
            const options = document.getElementById('options_text');
            const update = () => {
                panel.hidden = type.value !== 'select';
                options.disabled = type.value !== 'select';
                options.required = type.value === 'select';
            };
            type.addEventListener('change', update);
            update();
        })();
    </script>
@endsection
