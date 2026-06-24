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
     * Returns a new query builder for settings.
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Counts the total number of settings.
     */
    public function countAll(): int
    {
        return $this->model->query()->count();
    }

    /**
     * Creates a setting from a DTO.
     *
     * @param SettingsData $data
     * @return Builder|Model
     */
    public function createFromData(SettingsData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    /**
     * Updates a setting from a DTO.
     *
     * @param SettingsData $data
     * @return bool
     */
    public function updateFromData(SettingsData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }
}
