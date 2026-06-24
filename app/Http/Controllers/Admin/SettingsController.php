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
     * Shows the project settings list.
     * @return View
     */
    public function index(): View
    {
        return view('cp.settings.index')->with('title', __('interface.admin.settings.title'));
    }

    /**
     * Shows the setting creation form.
     *
     * @return View
     */
    public function create(): View
    {
        return view('cp.settings.create_edit')->with('title', __('interface.admin.settings.create_title'));
    }


    /**
     * Creates a setting from validated form data.
     *
     * @param StoreSettingsRequest $request
     * @return RedirectResponse
     */
    public function store(StoreSettingsRequest $request): RedirectResponse
    {
        $this->service->create(SettingsData::fromArray($request->validated()));

        return redirect(URL::route('cp.settings.index'))->with('success', __('interface.messages.information_successfully_added'));
    }

    /**
     * Shows the setting edit form.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->settings->find($id);
        abort_if(! $row, 404);

        return view('cp.settings.create_edit', compact('row'))->with('title', __('interface.admin.settings.edit_title'));
    }

    /**
     * Updates a setting from validated form data.
     *
     * @param UpdateSettingsRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $this->service->update(SettingsData::fromArray($request->validated()));

        return redirect(URL::route('cp.settings.index'))->with('success', __('interface.messages.data_updated'));
    }

    /**
     * Deletes a setting by ID from the validated request.
     *
     * @param DestroySettingsRequest $request
     * @return void
     */
    public function destroy(DestroySettingsRequest $request): void
    {
        $this->service->delete((int) $request->validated('id'));
    }
}
