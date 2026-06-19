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
     * Показывает список администраторов в панели управления.
     */
    public function index(): View
    {
        return view('cp.admin.index')->with('title', __('interface.admin.admin_users.title'));
    }

    /**
     * Показывает форму создания администратора.
     */
    public function create(): View
    {
        return view('cp.admin.create_edit')->with('title', __('interface.admin.admin_users.create_title'));
    }

    /**
     * Создает администратора из валидированных данных формы.
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
     * Показывает форму редактирования администратора.
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
     * Обновляет администратора из валидированных данных формы.
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
     * Удаляет администратора, не позволяя удалить текущего пользователя.
     *
     * @param DestroyAdminRequest $request
     * @return void
     */
    public function destroy(DestroyAdminRequest $request): void
    {
        $this->service->deleteExceptCurrent((int) $request->validated('id'), (int) Auth::id());
    }
}
