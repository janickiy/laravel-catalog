<?php

namespace App\Repositories;

use App\DTO\DataTransferObject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    /**
     * Returns all model records for simple unfiltered read scenarios.
     */
    public function all(): Collection;

    /**
     * Finds a record by primary key as the base safe model access method.
     */
    public function find(int|string $id): ?Model;

    /**
     * Creates a record from an attribute array as the shared low-level method for descendants.
     */
    public function create(array $data): Builder|Model;

    /**
     * Creates a record from a DTO so data writes go through a typed contract.
     */
    public function createFromDto(DataTransferObject $dto): Builder|Model;

    /**
     * Updates a record by primary key with an attribute array and returns the save result.
     */
    public function update(int|string $id, array $data): bool;

    /**
     * Updates a record by primary key using DTO data for typed update operations.
     */
    public function updateFromDto(int|string $id, DataTransferObject $dto): bool;

    /**
     * Deletes a record by primary key and returns false when it is not found.
     */
    public function delete(int|string $id): bool;

    /**
     * Deletes all model records without resetting the sequence for query builder cleanup.
     */
    public function deleteAll(): void;

    /**
     * Completely clears the model table and resets counters where the driver supports it.
     */
    public function truncate(): void;
}
