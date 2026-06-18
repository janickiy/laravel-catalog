<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Links\LinkData;
use App\Helpers\StringHelper;
use App\Http\Requests\Admin\DestroyLinkRequest;
use App\Http\Requests\Admin\ExportLinksRequest;
use App\Http\Requests\Admin\ImportLinksRequest;
use App\Http\Requests\Admin\StoreLinkRequest;
use App\Http\Requests\Admin\UpdateLinkRequest;
use App\Http\Requests\Admin\UpdateLinkStatusRequest;
use App\Repositories\LinksRepository;
use App\Services\Admin\LinkImportExportService;
use App\Services\Admin\LinkService;
use Illuminate\Support\Facades\URL;

class LinksController extends Controller
{
    public function __construct(
        private readonly LinksRepository $links,
        private readonly LinkService $service,
        private readonly LinkImportExportService $importExport,
    ) {
        parent::__construct();
    }

    public function index()
    {
        $status_list = $this->service->statusList();

        return view('cp.links.index', compact('status_list'))->with('title', 'Ссылки');
    }

    public function create()
    {
        $options = $this->service->catalogOptions();

        return view('cp.links.create_edit', compact('options'))->with('title', 'Добавление ссылки');
    }

    public function store(StoreLinkRequest $request)
    {
        $this->service->create(LinkData::fromArray($request->validated(), 1));

        return redirect(URL::route('cp.links.index'))->with('success', 'Информация успешно добавлена');
    }

    public function edit(int $id)
    {
        $row = $this->links->find($id);
        abort_if(! $row, 404);

        $options = $this->service->catalogOptions();

        return view('cp.links.create_edit', compact('row', 'options'))->with('title', 'Редактирование ссылки');
    }

    public function update(UpdateLinkRequest $request)
    {
        $this->service->update(LinkData::fromArray($request->validated()));

        return redirect(URL::route('cp.links.index'))->with('success', 'Данные обновлены');
    }

    public function destroy(DestroyLinkRequest $request): void
    {
        $this->service->delete((int) $request->validated('id'));
    }

    public function importForm()
    {
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.links.import', compact('maxUploadFileSize'))->with('title', 'Импорт');
    }

    public function importLink(ImportLinksRequest $request)
    {
        $count = $this->importExport->import($request->file('file'));

        return redirect(URL::route('cp.links.import'))->with('success', 'Импорт завершен. Импортировано ' . $count . ' ссылок');
    }

    public function export()
    {
        $options = $this->service->catalogOptions();

        return view('cp.links.export', compact('options'))->with('title', 'Экспорт');
    }

    public function exportLink(ExportLinksRequest $request)
    {
        return $this->importExport->export(
            $request->integer('catalog_id') ?: null,
            (string) $request->validated('export_type'),
            (string) $request->validated('compress'),
        );
    }

    public function statusLinks(UpdateLinkStatusRequest $request)
    {
        $this->service->updateStatuses($request->validated('activate') ?? [], (int) $request->validated('action'));

        return redirect(URL::route('cp.links.index'))->with('success', 'Данные обновлены');
    }
}
