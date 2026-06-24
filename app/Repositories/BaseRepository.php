<?php

namespace App\Repositories;

use App\DTO\DataTransferObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository implements RepositoryInterface
{
    /**
     * Stores the Eloquent model used by the concrete repository.
     */
    public function __construct(protected Model $model) {}

    /**
     * Creates a new record from an attribute array as the base operation for all descendants.
     */
    public function create(array $data): Builder|Model
    {
        return $this->model->create($data);
    }

    /**
     * Creates a new record from a DTO so services do not pass raw arrays directly.
     */
    public function createFromDto(DataTransferObject $dto): Builder|Model
    {
        return $this->create($dto->toArray());
    }

    /**
     * Updates a record by ID with the given attributes and returns false when it is not found.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int|string $id, array $data): bool
    {
        $model = $this->model->find($id);

        if ($model) {
            return $model->fill($data)->save();
        }

        return false;
    }

    /**
     * Updates a record by ID using DTO data for the shared typed update approach.
     */
    public function updateFromDto(int|string $id, DataTransferObject $dto): bool
    {
        return $this->update($id, $dto->toArray());
    }

    /**
     * Returns all records from the model table for simple unfiltered selections only.
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Finds a record by primary key and returns null when it does not exist.
     */
    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Deletes a record by primary key for the shared delete operation in descendants.
     */
    public function delete(int|string $id): bool
    {
        $model = $this->model->find($id);
        if ($model) {
            $model->delete();

            return true;
        }

        return false;
    }

    /**
     * Deletes all records through the query builder without recreating the table.
     */
    public function deleteAll(): void
    {
        $this->model->query()->delete();
    }

    /**
     * Completely clears the model table for maintenance cleanup and tests.
     */
    public function truncate(): void
    {
        $this->model->truncate();
    }
}
