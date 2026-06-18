<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\DataTableService;

class DataTableController extends Controller
{
    public function __construct(private readonly DataTableService $dataTables)
    {
        parent::__construct();
    }

    public function getAdmin(): mixed
    {
        return $this->dataTables->admins();
    }

    public function getLinks(): mixed
    {
        return $this->dataTables->links();
    }

    public function getFeedback(): mixed
    {
        return $this->dataTables->feedback();
    }

    public function getSettings(): mixed
    {
        return $this->dataTables->settings();
    }
}
