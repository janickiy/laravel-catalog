<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Catalog\CatalogData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\DeleteCatalogRequest;
use App\Http\Requests\Admin\StoreCatalogRequest;
use App\Http\Requests\Admin\UpdateCatalogRequest;
use App\Repositories\CatalogRepository;
use App\Services\Admin\CatalogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;

class CatalogController extends Controller
{
    public function __construct(
        private readonly CatalogRepository $catalogs,
        private readonly CatalogService $service,
    ) {
        parent::__construct();
    }

    /**
     * Показывает дерево разделов каталога в панели управления.
     */
    public function index(): View
    {
        $catalogTree = $this->service->treeHtml();

        return view('cp.catalog.index', compact('catalogTree'))->with('title', 'Категории');
    }

    /**
     * Показывает форму создания раздела каталога.
     *
     * @param int $parent_id
     * @return View
     */
    public function create(int $parent_id = 0): View
    {
        $options = $this->service->options();
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.catalog.create_edit', compact('parent_id', 'options', 'maxUploadFileSize'))->with('title', 'Добавление категории');
    }

    /**
     * Создает раздел каталога и сохраняет его изображение.
     *
     * @param StoreCatalogRequest $request
     * @return RedirectResponse
     * @throws \Gumlet\ImageResizeException
     */
    public function store(StoreCatalogRequest $request): RedirectResponse
    {
        $this->service->create(
            CatalogData::fromArray($request->validated()),
            $request->file('image'),
        );

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Информация успешно добавлена');
    }

    /**
     * Показывает форму редактирования раздела каталога.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->catalogs->find($id);
        abort_if(! $row, 404);

        $options = $this->service->options();
        $parent_id = $row->parent_id;
        $maxUploadFileSize = StringHelper::maxUploadFileSize();
        unset($options[$id]);

        return view('cp.catalog.create_edit', compact('row', 'parent_id', 'options', 'maxUploadFileSize'))->with('title', 'Редактирование категории');
    }

    /**
     * Обновляет раздел каталога и при необходимости заменяет изображение.
     *
     * @param UpdateCatalogRequest $request
     * @return RedirectResponse
     * @throws \Gumlet\ImageResizeException
     */
    public function update(UpdateCatalogRequest $request): RedirectResponse
    {
        $this->service->update(
            CatalogData::fromArray($request->validated()),
            $request->file('image'),
            $request->input('pic'),
        );

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Данные обновлены');
    }

    /**
     * Удаляет раздел каталога вместе с дочерними разделами.
     *
     * @param DeleteCatalogRequest $request
     * @return RedirectResponse
     */
    public function delete(DeleteCatalogRequest $request): RedirectResponse
    {
        $this->service->deleteWithChildren((int) $request->validated('id'));

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Данные удалены');
    }
}
