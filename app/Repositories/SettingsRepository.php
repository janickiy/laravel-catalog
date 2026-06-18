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

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function createFromData(SettingsData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    public function updateFromData(SettingsData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }
}
