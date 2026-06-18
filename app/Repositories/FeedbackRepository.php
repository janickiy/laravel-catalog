<?php

namespace App\Repositories;

use App\DTO\Frontend\FeedbackMessageData;
use App\Models\Feedback;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FeedbackRepository extends BaseRepository
{
    public function __construct(Feedback $model)
    {
        parent::__construct($model);
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function createFromData(FeedbackMessageData $data): Builder|Model
    {
        return $this->create($data->toArray());
    }
}
