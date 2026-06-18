<?php

namespace App\Services\Admin;

use App\Enums\LinkStatus;
use App\Repositories\AdminRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\LinksRepository;
use App\Repositories\SettingsRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\Facades\DataTables;

class DataTableService
{
    public function __construct(
        private readonly AdminRepository $admins,
        private readonly LinksRepository $links,
        private readonly FeedbackRepository $feedback,
        private readonly SettingsRepository $settings,
    ) {
    }

    public function admins(): mixed
    {
        return DataTables::of($this->admins->query())
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary" href="' . URL::route('cp.admin.edit', ['id' => $row->id]) . '"><span class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = $row->id !== Auth::id()
                    ? '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>'
                    : '';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function links(): mixed
    {
        return DataTables::of($this->links->dataTableQuery())
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary" href="' . URL::route('cp.links.edit', ['id' => $row->id]) . '"><span class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->editColumn('catalog', fn ($row) => $row->catalog_id == 0 ? 'Разное' : $row->catalog)
            ->addColumn('status_link', fn ($links) => $links->status)
            ->editColumn('status', fn ($row) => LinkStatus::labelFor($row->status))
            ->addColumn('checkbox', fn ($links) => '<input type="checkbox" title="Отметить/Снять отметку" value="' . $links->id . '" name="activate[]">')
            ->rawColumns(['action', 'checkbox'])
            ->make(true);
    }

    public function feedback(): mixed
    {
        return DataTables::of($this->feedback->query())->make(true);
    }

    public function settings(): mixed
    {
        return DataTables::of($this->settings->query())
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary" href="' . URL::route('cp.settings.edit', ['id' => $row->id]) . '"><span class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
