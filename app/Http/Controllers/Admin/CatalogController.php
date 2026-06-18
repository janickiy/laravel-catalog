<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Catalog\CatalogData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\DeleteCatalogRequest;
use App\Http\Requests\Admin\StoreCatalogRequest;
use App\Http\Requests\Admin\UpdateCatalogRequest;
use App\Repositories\CatalogRepository;
use App\Services\Admin\CatalogService;
use Illuminate\Support\Facades\URL;

class CatalogController extends Controller
{
    public function __construct(
        private readonly CatalogRepository $catalogs,
        private readonly CatalogService $service,
    ) {
        parent::__construct();
    }

    public function index()
    {
        $cats = $this->service->tree();

        return view('cp.catalog.index', compact('cats'))->with('title', 'Категории');
    }

    public function create(int $parent_id = 0)
    {
        $options = $this->service->options();
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.catalog.create_edit', compact('parent_id', 'options', 'maxUploadFileSize'))->with('title', 'Добавление категории');
    }

    public function store(StoreCatalogRequest $request)
    {
        $this->service->create(
            CatalogData::fromArray($request->validated()),
            $request->file('image'),
        );

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Информация успешно добавлена');
    }

    public function edit(int $id)
    {
        $row = $this->catalogs->find($id);
        abort_if(! $row, 404);

        $options = $this->service->options();
        $parent_id = $row->parent_id;
        $maxUploadFileSize = StringHelper::maxUploadFileSize();
        unset($options[$id]);

        return view('cp.catalog.create_edit', compact('row', 'parent_id', 'options', 'maxUploadFileSize'))->with('title', 'Редактирование категории');
    }

    public function update(UpdateCatalogRequest $request)
    {
        $this->service->update(
            CatalogData::fromArray($request->validated()),
            $request->file('image'),
            $request->input('pic'),
        );

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Данные обновлены');
    }

    public function delete(DeleteCatalogRequest $request)
    {
        $this->service->deleteWithChildren((int) $request->validated('id'));

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Данные удалены');
    }
}
