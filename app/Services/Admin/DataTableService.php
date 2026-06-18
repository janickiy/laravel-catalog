<?php

namespace App\Services\Admin;

use App\Enums\LinkStatus;
use App\Repositories\AdminRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\LinksRepository;
use App\Repositories\SettingsRepository;
use DateTimeInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
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
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary" href="' . route('cp.admin.edit', ['id' => $row->id]) . '"><span class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = $row->id !== Auth::id()
                    ? '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '" data-delete-url="' . route('cp.admin.destroy', ['id' => $row->id]) . '"><span class="fa fa-remove"></span></a>'
                    : '';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->editColumn('created_at', fn ($row) => $this->formatDate($row->created_at))
            ->editColumn('updated_at', fn ($row) => $this->formatDate($row->updated_at))
            ->rawColumns(['action'])
            ->make(true);
    }

    public function links(): mixed
    {
        return DataTables::of($this->links->dataTableQuery())
            ->addColumn('action', function ($row) {
                $showBtn = '<a title="просмотр" class="btn btn-sm btn-outline-info" href="' . route('cp.links.show', ['id' => $row->id]) . '"><span class="bi bi-eye"></span></a>';
                $editBtn = '<a title="редактировать" class="btn btn-sm btn-outline-primary" href="' . route('cp.links.edit', ['id' => $row->id]) . '"><span class="bi bi-pencil-square"></span></a>';
                $deleteBtn = '<a title="удалить" class="btn btn-sm btn-outline-danger deleteRow" id="' . $row->id . '" data-delete-url="' . route('cp.links.destroy', ['id' => $row->id]) . '"><span class="bi bi-trash"></span></a>';

                return '<div class="btn-group btn-group-sm" role="group">' . $showBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->editColumn('catalog', fn ($row) => $row->catalog_id === null ? 'Разное' : $row->catalog)
            ->editColumn('status', fn ($row) => '<span class="badge ' . LinkStatus::cssColorFor($row->status) . '">' . e(LinkStatus::labelFor($row->status)) . '</span>')
            ->editColumn('created_at', fn ($row) => $this->formatDate($row->created_at))
            ->addColumn('checkbox', fn ($links) => '<input type="checkbox" title="Отметить/Снять отметку" value="' . $links->id . '" name="activate[]">')
            ->rawColumns(['action', 'checkbox', 'status'])
            ->make(true);
    }

    public function feedback(): mixed
    {
        return DataTables::of($this->feedback->query())
            ->editColumn('created_at', fn ($row) => $this->formatDate($row->created_at))
            ->editColumn('updated_at', fn ($row) => $this->formatDate($row->updated_at))
            ->make(true);
    }

    public function settings(): mixed
    {
        return DataTables::of($this->settings->query())
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary" href="' . route('cp.settings.edit', ['id' => $row->id]) . '"><span class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '" data-delete-url="' . route('cp.settings.destroy', ['id' => $row->id]) . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    private function formatDate(mixed $date): string
    {
        if ($date instanceof DateTimeInterface) {
            return $date->format('Y-m-d H:i:s');
        }

        if ($date === null || $date === '') {
            return '';
        }

        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}
