<?php

namespace App\Http\Controllers\Admin;

use App\Models\{Admin, Links, Settings, Feedback};
use Illuminate\Support\Facades\Auth;
use DataTables;
use URL;

class DataTableController extends Controller
{

    /**
     * @return mixed
     */
    public function getAdmin()
    {
        $row = Admin::query();

        return Datatables::of($row)
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.admin.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';

                if ($row->id != Auth::id())
                    $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';
                else
                    $deleteBtn = '';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getLinks()
    {

        $row = Links::selectRaw('links.id,links.name,links.catalog_id,links.url,links.city, links.phone,links.created_at,links.description, links.status, links.views, catalog.name AS catalog')
            ->leftJoin('catalog', 'catalog.id', '=', 'links.catalog_id')
            ->groupBy('catalog.name')
            ->groupBy('links.id')
            ->groupBy('links.name')
            ->groupBy('links.url')
            ->groupBy('links.phone')
            ->groupBy('links.city')
            ->groupBy('links.created_at')
            ->groupBy('links.status')
            ->groupBy('links.catalog_id')
            ->groupBy('links.views')
            ->groupBy('links.description');

        return Datatables::of($row)
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary" href="' . URL::route('cp.links.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })

            ->editColumn('catalog', function ($row) {
                return $row->catalog_id == 0 ? 'Разное' : $row->catalog;
            })

            ->addColumn('status_link', function ($links) {
                return $links->status;
            })

            ->editColumn('status', function ($row) {
                return Links::linkStatus($row->status);
            })

            ->addColumn('checkbox', function ($links) {
                return '<input type="checkbox" title="Отметить/Снять отметку" value="' . $links->id . '" name="activate[]">';
            })

            ->rawColumns(['action','checkbox'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getFeedback()
    {
       $row  = Feedback::query();

        return Datatables::of($row)

            ->make(true);
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        $row = Settings::query();

        return Datatables::of($row)
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.settings.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])->make(true);
    }
}
