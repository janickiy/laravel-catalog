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
     * Returns a new query builder for feedback messages.
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Counts the total number of feedback messages.
     */
    public function countAll(): int
    {
        return $this->model->query()->count();
    }

    /**
     * Returns the latest feedback messages.
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
     * Creates a feedback message from a DTO.
     *
     * @param FeedbackMessageData $data
     * @return Builder|Model
     */
    public function createFromData(FeedbackMessageData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }
}
