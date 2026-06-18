<?php

namespace App\Services\Admin;

use App\Repositories\LinksRepository;

class DashboardService
{
    public function __construct(private readonly LinksRepository $links)
    {
    }

    public function counters(): array
    {
        return [
            'new' => $this->links->countByStatus(0),
            'publish' => $this->links->countByStatus(1),
            'black' => $this->links->countByStatus(2),
        ];
    }
}
