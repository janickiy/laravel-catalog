@php
    $admin = $admin ?? false;
    $currentImage = $currentImage ?? null;
    $hasCurrentImage = $currentImage && is_file(public_path('uploads/url/' . $currentImage));
@endphp
<div class="screenshot-upload {{ $admin ? 'screenshot-upload--admin' : '' }}" data-screenshot-upload
     data-error-type="{{ __('submission.invalid_type') }}" data-error-size="{{ __('submission.invalid_size') }}"
     data-error-dimensions="{{ __('submission.invalid_dimensions') }}" data-error-read="{{ __('submission.invalid_image') }}">
    <label for="screenshot" class="form-label {{ $admin ? 'fw-semibold' : '' }}">{{ __('submission.screenshot') }} <span class="{{ $admin ? 'fw-normal text-secondary small' : 'screenshot-upload__optional' }}">{{ __('submission.optional') }}</span></label>
    @if($hasCurrentImage)
        <figure class="screenshot-upload__current">
            <img src="{{ asset('uploads/url/' . $currentImage) }}" alt="{{ __('submission.current_screenshot') }}" loading="lazy" decoding="async">
            <figcaption>{{ __('submission.current_screenshot') }}</figcaption>
        </figure>
    @endif
    <div class="screenshot-upload__zone">
        <span class="screenshot-upload__symbol" aria-hidden="true"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8" cy="8" r="1.5"/><path d="m3 17 5-5 4 4 4-6 5 7"/></svg></span>
        <div class="screenshot-upload__controls">
            <p class="screenshot-upload__intro">{{ __('submission.screenshot_intro') }}</p>
            <input type="file" id="screenshot" name="screenshot" accept="image/jpeg,image/png,image/webp" class="{{ $admin ? 'form-control' : 'screenshot-upload__input' }}{{ $errors->has('screenshot') ? ' is-invalid' : '' }}" aria-describedby="screenshot-hint screenshot-client-error{{ $errors->has('screenshot') ? ' screenshot-error' : '' }}" aria-invalid="{{ $errors->has('screenshot') ? 'true' : 'false' }}" data-screenshot-input>
            <p id="screenshot-hint" class="screenshot-upload__hint">{{ __('submission.screenshot_hint') }}</p>
        </div>
    </div>
    <p id="screenshot-client-error" class="{{ $admin ? 'text-danger small mt-2' : 'form-error' }}" data-screenshot-error role="alert" hidden></p>
    @if($errors->has('screenshot'))<div id="screenshot-error" class="{{ $admin ? 'invalid-feedback d-block' : 'form-error' }}">{{ $errors->first('screenshot') }}</div>@endif
    <figure class="screenshot-upload__preview" data-screenshot-preview hidden>
        <img data-screenshot-image alt="{{ __('submission.selected_screenshot') }}">
        <figcaption>{{ __('submission.selected_screenshot') }}</figcaption>
    </figure>
    <div class="screenshot-upload__selection" data-screenshot-selection hidden>
        <span data-screenshot-filename role="status" aria-live="polite"></span>
        <button type="button" class="{{ $admin ? 'btn btn-sm btn-outline-secondary' : 'screenshot-upload__clear' }}" data-screenshot-clear>{{ __('submission.clear_file') }}</button>
    </div>
    @if($admin && $hasCurrentImage)
        <div class="form-check mt-3">
            <input type="checkbox" class="form-check-input" name="remove_screenshot" id="remove-screenshot" value="1" @checked(old('remove_screenshot'))>
            <label class="form-check-label" for="remove-screenshot">{{ __('submission.remove_screenshot') }}</label>
        </div>
        <p class="form-text mb-0">{{ __('submission.replacement_hint') }}</p>
        @if($errors->has('remove_screenshot'))<div class="invalid-feedback d-block">{{ $errors->first('remove_screenshot') }}</div>@endif
    @endif
</div>
