<?php

namespace App\Repositories;

use App\DTO\Admin\AdminData;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AdminRepository extends BaseRepository
{
    public function __construct(Admin $model)
    {
        parent::__construct($model);
    }

    /**
     * Returns a new query builder for the administrator model.
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Counts the total number of administrators.
     */
    public function countAll(): int
    {
        return $this->model->query()->count();
    }

    /**
     * Returns the latest added administrators.
     *
     * @param int $limit
     * @return Collection
     */
    public function latest(int $limit): Collection
    {
        return $this->model->query()
            ->orderByDesc('id')
            ->take($limit)
            ->get();
    }

    /**
     * Creates an administrator from a DTO.
     *
     * @param AdminData $data
     * @return Builder|Model
     */
    public function createFromData(AdminData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    /**
     * Updates an administrator from a DTO.
     *
     * @param AdminData $data
     * @return bool
     */
    public function updateFromData(AdminData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }
}
