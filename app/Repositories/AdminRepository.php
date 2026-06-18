<?php

namespace App\Repositories;

use App\DTO\Admin\AdminData;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AdminRepository extends BaseRepository
{
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function createFromData(AdminData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    public function updateFromData(AdminData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }
}
