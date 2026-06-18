<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Links\LinkData;
use App\Enums\LinkStatus;
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
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class LinksController extends Controller
{
    public function __construct(
        private readonly LinksRepository $links,
        private readonly LinkService $service,
        private readonly LinkImportExportService $importExport,
    ) {
        parent::__construct();
    }

    /**
     * Показывает список ссылок в панели управления.
     */
    public function index(): View
    {
        $status_list = $this->service->statusList();

        return view('cp.links.index', compact('status_list'))->with('title', 'Ссылки');
    }

    /**
     * Показывает форму создания ссылки.
     *
     * @return View
     */
    public function create(): View
    {
        $options = $this->service->catalogOptions();

        return view('cp.links.create_edit', compact('options'))->with('title', 'Добавление ссылки');
    }

    /**
     * Создает опубликованную ссылку из валидированных данных формы.
     *
     * @param StoreLinkRequest $request
     * @return RedirectResponse
     */
    public function store(StoreLinkRequest $request): RedirectResponse
    {
        $this->service->create(LinkData::fromArray($request->validated(), LinkStatus::Published->value));

        return redirect(URL::route('cp.links.index'))->with('success', 'Информация успешно добавлена');
    }

    /**
     * Показывает детальную страницу ссылки в админке.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $link = $this->links->findForAdmin($id);
        abort_if(! $link, 404);

        return view('cp.links.show', compact('link'))->with('title', 'Просмотр ссылки');
    }

    /**
     * Показывает форму редактирования ссылки.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->links->find($id);
        abort_if(! $row, 404);

        $options = $this->service->catalogOptions();

        return view('cp.links.create_edit', compact('row', 'options'))->with('title', 'Редактирование ссылки');
    }

    /**
     * Обновляет ссылку из валидированных данных формы.
     *
     * @param UpdateLinkRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateLinkRequest $request): RedirectResponse
    {
        $this->service->update(LinkData::fromArray($request->validated()));

        return redirect(URL::route('cp.links.index'))->with('success', 'Данные обновлены');
    }

    /**
     * Удаляет ссылку по идентификатору из валидированного запроса.
     *
     * @param DestroyLinkRequest $request
     * @return void
     */
    public function destroy(DestroyLinkRequest $request): void
    {
        $this->service->delete((int) $request->validated('id'));
    }

    /**
     * Показывает форму импорта ссылок.
     *
     * @return View
     */
    public function importForm(): View
    {
        $maxUploadFileSize = StringHelper::maxUploadFileSize();

        return view('cp.links.import', compact('maxUploadFileSize'))->with('title', 'Импорт');
    }

    /**
     * Импортирует ссылки из загруженного файла.
     *
     * @param ImportLinksRequest $request
     * @return RedirectResponse
     */
    public function importLink(ImportLinksRequest $request): RedirectResponse
    {
        $count = $this->importExport->import($request->file('file'));

        return redirect(URL::route('cp.links.import'))->with('success', 'Импорт завершен. Импортировано '.$count.' ссылок');
    }

    /**
     * Показывает форму экспорта ссылок.
     */
    public function export(): View
    {
        $options = $this->service->catalogOptions();

        return view('cp.links.export', compact('options'))->with('title', 'Экспорт');
    }

    /**
     * Отдает файл экспорта ссылок в выбранном формате.
     *
     * @param ExportLinksRequest $request
     * @return Response|BinaryFileResponse
     */
    public function exportLink(ExportLinksRequest $request): Response|BinaryFileResponse
    {
        $catalogId = $request->validated('catalog_id');

        return $this->importExport->export(
            $catalogId === null ? null : (int) $catalogId,
            (string) $request->validated('export_type'),
            (string) $request->validated('compress'),
        );
    }

    /**
     * Массово обновляет статусы выбранных ссылок.
     *
     * @param UpdateLinkStatusRequest $request
     * @return RedirectResponse
     */
    public function statusLinks(UpdateLinkStatusRequest $request): RedirectResponse
    {
        $this->service->updateStatuses($request->validated('activate') ?? [], (int) $request->validated('action'));

        return redirect(URL::route('cp.links.index'))->with('success', 'Данные обновлены');
    }
}
