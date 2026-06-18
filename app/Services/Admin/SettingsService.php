<?php

namespace App\Services\Admin;

use App\DTO\Settings\SettingsData;
use App\Repositories\SettingsRepository;

class SettingsService
{
    public function __construct(private readonly SettingsRepository $settings) {}

    /**
     * Создает настройку из DTO.
     */
    public function create(SettingsData $data): void
    {
        $this->settings->createFromData($data);
    }


    /**
     * Обновляет настройку из DTO.
     *
     * @param SettingsData $data
     * @return bool
     */
    public function update(SettingsData $data): bool
    {
        return $this->settings->updateFromData($data);
    }

    /**
     * Удаляет настройку по идентификатору.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->settings->delete($id);
    }
}
