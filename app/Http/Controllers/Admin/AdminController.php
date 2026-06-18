<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\AdminData;
use App\Http\Requests\Admin\DestroyAdminRequest;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Repositories\AdminRepository;
use App\Services\Admin\AdminService;
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

    public function index()
    {
        return view('cp.admin.index')->with('title', 'Администраторы');
    }

    public function create()
    {
        return view('cp.admin.create_edit')->with('title', 'Добавление администратора');
    }

    public function store(StoreAdminRequest $request)
    {
        $this->service->create(AdminData::fromArray($request->validated()));

        return redirect(URL::route('cp.admin.index'))->with('success', trans('message.information_successfully_added'));
    }

    public function edit(int $id)
    {
        $row = $this->admins->find($id);
        abort_if(! $row, 404);

        return view('cp.admin.create_edit', compact('row'))->with('title', 'Редактирование администратора');
    }

    public function update(UpdateAdminRequest $request)
    {
        $this->service->update(AdminData::fromArray($request->validated()));

        return redirect(URL::route('cp.admin.index'))->with('success', trans('message.data_updated'));
    }

    public function destroy(DestroyAdminRequest $request): void
    {
        $this->service->deleteExceptCurrent((int) $request->validated('id'), (int) Auth::id());
    }
}
