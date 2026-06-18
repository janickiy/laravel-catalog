<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Settings\SettingsData;
use App\Http\Requests\Admin\DestroySettingsRequest;
use App\Http\Requests\Admin\StoreSettingsRequest;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Repositories\SettingsRepository;
use App\Services\Admin\SettingsService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;

class SettingsController extends Controller
{
    public function __construct(
        private readonly SettingsRepository $settings,
        private readonly SettingsService $service,
    ) {
        parent::__construct();
    }

    /**
     * Показывает список настроек проекта
     * @return View
     */
    public function index(): View
    {
        return view('cp.settings.index')->with('title', 'Настройки');
    }

    /**
     * Показывает форму создания настройки.
     *
     * @return View
     */
    public function create(): View
    {
        return view('cp.settings.create_edit')->with('title', 'Добавление настроек');
    }


    /**
     * Создает настройку из валидированных данных формы
     *
     * @param StoreSettingsRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSettingsRequest $request): RedirectResponse
    {
        $this->service->create(SettingsData::fromArray($request->validated()));

        return redirect(URL::route('cp.settings.index'))->with('success', 'Информация успешно добавлена');
    }

    /**
     * Показывает форму редактирования настройки.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->settings->find($id);
        abort_if(! $row, 404);

        return view('cp.settings.create_edit', compact('row'))->with('title', 'Редактирование настроек');
    }

    /**
     * Обновляет настройку из валидированных данных формы
     *
     * @param UpdateSettingsRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $this->service->update(SettingsData::fromArray($request->validated()));

        return redirect(URL::route('cp.settings.index'))->with('success', 'Данные обновлены');
    }

    /**
     * Удаляет настройку по идентификатору из валидированного запроса.
     *
     * @param DestroySettingsRequest $request
     * @return void
     */
    public function destroy(DestroySettingsRequest $request): void
    {
        $this->service->delete((int) $request->validated('id'));
    }
}
