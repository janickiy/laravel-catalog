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
     * Возвращает новый query builder для модели администратора.
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Считает общее количество администраторов.
     */
    public function countAll(): int
    {
        return $this->model->query()->count();
    }

    /**
     * Возвращает последних добавленных администраторов.
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
     * Создает администратора из DTO.
     *
     * @param AdminData $data
     * @return Builder|Model
     */
    public function createFromData(AdminData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }

    /**
     * Обновляет администратора из DTO.
     *
     * @param AdminData $data
     * @return bool
     */
    public function updateFromData(AdminData $data): bool
    {
        return $this->update($data->id(), $data->toArray());
    }
}
