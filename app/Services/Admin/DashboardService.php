<?php

namespace App\Services\Admin;

use App\Enums\LinkStatus;
use App\Repositories\LinksRepository;

class DashboardService
{
    public function __construct(private readonly LinksRepository $links)
    {
    }

    public function counters(): array
    {
        return [
            'new' => $this->links->countByStatus(LinkStatus::Pending),
            'publish' => $this->links->countByStatus(LinkStatus::Published),
            'black' => $this->links->countByStatus(LinkStatus::Blocked),
        ];
    }
}
