<?php

namespace App\Repositories;

use App\DTO\Frontend\FeedbackMessageData;
use App\Models\Feedback;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FeedbackRepository extends BaseRepository
{
    public function __construct(Feedback $model)
    {
        parent::__construct($model);
    }

    /**
     * Возвращает новый query builder для сообщений обратной связи.
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Считает общее количество сообщений обратной связи.
     */
    public function countAll(): int
    {
        return $this->model->query()->count();
    }

    /**
     * Возвращает последние сообщения обратной связи.
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
     * Создает сообщение обратной связи из DTO
     *
     * @param FeedbackMessageData $data
     * @return Builder|Model
     */
    public function createFromData(FeedbackMessageData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }
}
