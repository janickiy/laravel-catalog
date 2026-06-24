<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\UpdateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UpdateController extends Controller
{
    public function __construct(private readonly UpdateService $updates)
    {
        parent::__construct();
    }

    /**
     * Shows the project update check and launch page.
     */
    public function index(): View
    {
        return view('cp.update.index', [
            'title' => __('interface.admin.updates.title'),
            'update' => $this->updates->check(app()->getLocale()),
            'steps' => $this->updates->steps(),
        ]);
    }

    /**
     * Runs one AJAX step of the project update.
     */
    public function run(Request $request): JsonResponse
    {
        $step = trim((string) $request->input('step', ''));

        return response()->json($this->updates->runStep($step, app()->getLocale()));
    }
}
