<?php

namespace App\Services\Admin;

use App\DTO\Settings\SettingsData;
use App\Repositories\SettingsRepository;

class SettingsService
{
    public function __construct(private readonly SettingsRepository $settings)
    {
    }

    public function create(SettingsData $data): void
    {
        $this->settings->createFromData($data);
    }

    public function update(SettingsData $data): bool
    {
        return $this->settings->updateFromData($data);
    }

    public function delete(int $id): bool
    {
        return $this->settings->delete($id);
    }
}
