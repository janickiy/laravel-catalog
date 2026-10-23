<?php

namespace App\Http\Requests\Concerns;

use App\Services\Links\CustomFieldService;
use App\Services\Links\LinkScreenshotService;

trait ValidatesLinkExtras
{
    protected function extrasRules(bool $admin = false): array
    {
        return ['screenshot' => LinkScreenshotService::rules()]
            + ($admin ? ['remove_screenshot' => ['nullable', 'boolean']] : [])
            + app(CustomFieldService::class)->rules($admin);
    }

    protected function extrasMessages(): array
    {
        return [
            'screenshot.image' => __('link_validation.screenshot_image'),
            'screenshot.mimes' => __('link_validation.screenshot_image'),
            'screenshot.max' => __('link_validation.screenshot_max'),
            'screenshot.dimensions' => __('link_validation.screenshot_dimensions'),
            'screenshot.uploaded' => __('link_validation.screenshot_uploaded'),
            'custom_fields.array' => __('link_validation.fields_invalid'),
            'custom_fields.size' => __('link_validation.fields_invalid'),
            'custom_fields.*.required' => __('link_validation.required'),
            'custom_fields.*.accepted' => __('link_validation.accepted'),
            'custom_fields.*.string' => __('link_validation.string'),
            'custom_fields.*.max' => __('link_validation.max'),
            'custom_fields.*.url' => __('link_validation.url'),
            'custom_fields.*.numeric' => __('link_validation.numeric'),
            'custom_fields.*.between' => __('link_validation.between'),
            'custom_fields.*.in' => __('link_validation.in'),
            'custom_fields.*.boolean' => __('link_validation.boolean'),
        ];
    }
}
