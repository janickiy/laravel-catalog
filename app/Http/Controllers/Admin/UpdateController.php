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
     * Показывает страницу проверки и запуска обновления проекта.
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
     * Выполняет один AJAX-шаг обновления проекта.
     */
    public function run(Request $request): JsonResponse
    {
        $step = trim((string) $request->input('step', ''));

        return response()->json($this->updates->runStep($step, app()->getLocale()));
    }
}
