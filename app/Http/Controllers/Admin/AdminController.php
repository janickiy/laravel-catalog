<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\AdminData;
use App\Http\Requests\Admin\DestroyAdminRequest;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Repositories\AdminRepository;
use App\Services\Admin\AdminService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AdminController extends Controller
{
    public function __construct(
        private readonly AdminRepository $admins,
        private readonly AdminService $service,
    ) {
        parent::__construct();
    }

    /**
     * Shows the administrator list in the control panel.
     */
    public function index(): View
    {
        return view('cp.admin.index')->with('title', __('interface.admin.admin_users.title'));
    }

    /**
     * Shows the administrator creation form.
     */
    public function create(): View
    {
        return view('cp.admin.create_edit')->with('title', __('interface.admin.admin_users.create_title'));
    }

    /**
     * Creates an administrator from validated form data.
     *
     * @param StoreAdminRequest $request
     * @return RedirectResponse
     */
    public function store(StoreAdminRequest $request): RedirectResponse
    {
        $this->service->create(AdminData::fromArray($request->validated()));

        return redirect(URL::route('cp.admin.index'))->with('success', __('interface.messages.information_successfully_added'));
    }

    /**
     * Shows the administrator edit form.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->admins->find($id);
        abort_if(! $row, 404);

        return view('cp.admin.create_edit', compact('row'))->with('title', __('interface.admin.admin_users.edit_title'));
    }


    /**
     * Updates an administrator from validated form data.
     *
     * @param UpdateAdminRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateAdminRequest $request): RedirectResponse
    {
        $this->service->update(AdminData::fromArray($request->validated()));

        return redirect(URL::route('cp.admin.index'))->with('success', __('interface.messages.data_updated'));
    }

    /**
     * Deletes an administrator while preventing deletion of the current user.
     *
     * @param DestroyAdminRequest $request
     * @return void
     */
    public function destroy(DestroyAdminRequest $request): void
    {
        $this->service->deleteExceptCurrent((int) $request->validated('id'), (int) Auth::id());
    }
}
