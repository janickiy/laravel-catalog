<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard)
    {
        parent::__construct();
    }

    public function index()
    {
        return view('cp.dashboard.index', $this->dashboard->data())->with('title', 'Главная');
    }
}
