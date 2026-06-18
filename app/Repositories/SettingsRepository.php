<?php

namespace App\Repositories;

use App\DTO\Settings\SettingsData;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SettingsRepository extends BaseRepository
{
    public function __construct(Settings $model)
    {
        parent::__construct($model);
    }

    /**
     * Возвращает новый query builder для настроек.
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Считает общее количество настроек.
     */
    public function countAll(): int
    {
        return $this->model->query()->count();
    }

    /**
     * Создает настройку из DTO.
     *
     * @param SettingsData $data
     * @return Builder|Model
     */
    public function createFromData(SettingsData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    /**
     * Обновляет настройку из DTO.
     *
     * @param SettingsData $data
     * @return bool
     */
    public function updateFromData(SettingsData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }
}
