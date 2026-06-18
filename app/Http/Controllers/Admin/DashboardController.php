<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\DashboardService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard)
    {
        parent::__construct();
    }

    /**
     * Показывает главный dashboard административной панели.
     */
    public function index(): View
    {
        return view('cp.dashboard.index', $this->dashboard->data())->with('title', 'Главная');
    }
}
