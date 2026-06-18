<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Settings\SettingsData;
use App\Http\Requests\Admin\DestroySettingsRequest;
use App\Http\Requests\Admin\StoreSettingsRequest;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Repositories\SettingsRepository;
use App\Services\Admin\SettingsService;
use Illuminate\Support\Facades\URL;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingsRepository $settings,
        private readonly SettingsService $service,
    ) {
        parent::__construct();
    }

    public function index()
    {
        return view('cp.settings.index')->with('title', 'Настройки');
    }

    public function create()
    {
        return view('cp.settings.create_edit')->with('title', 'Добавление настроек');
    }

    public function store(StoreSettingsRequest $request)
    {
        $this->service->create(SettingsData::fromArray($request->validated()));

        return redirect(URL::route('cp.settings.index'))->with('success', 'Информация успешно добавлена');
    }

    public function edit(int $id)
    {
        $row = $this->settings->find($id);
        abort_if(! $row, 404);

        return view('cp.settings.create_edit', compact('row'))->with('title', 'Редактирование настроек');
    }

    public function update(UpdateSettingsRequest $request)
    {
        $this->service->update(SettingsData::fromArray($request->validated()));

        return redirect(URL::route('cp.settings.index'))->with('success', 'Данные обновлены');
    }

    public function destroy(DestroySettingsRequest $request): void
    {
        $this->service->delete((int) $request->validated('id'));
    }
}
