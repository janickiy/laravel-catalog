<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\DataTableService;

class DataTableController extends Controller
{
    public function __construct(private readonly DataTableService $dataTables)
    {
        parent::__construct();
    }

    /**
     * Returns JSON data for the administrators table.
     */
    public function getAdmin(): mixed
    {
        return $this->dataTables->admins();
    }

    /**
     * Returns JSON data for the links table.
     */
    public function getLinks(): mixed
    {
        return $this->dataTables->links();
    }

    /**
     * Returns JSON data for the feedback messages table.
     */
    public function getFeedback(): mixed
    {
        return $this->dataTables->feedback();
    }

    /**
     * Returns JSON data for the settings table.
     */
    public function getSettings(): mixed
    {
        return $this->dataTables->settings();
    }
}
