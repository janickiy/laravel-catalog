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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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

        return view('cp.links.index', compact('status_list'))->with('title', __('interface.admin.links.title'));
    }

    /**
     * Показывает форму создания ссылки.
     *
     * @return View
     */
    public function create(): View
    {
        $options = $this->service->catalogOptions();

        return view('cp.links.create_edit', compact('options'))->with('title', __('interface.admin.links.create_title'));
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

        return redirect(URL::route('cp.links.index'))->with('success', __('interface.messages.information_successfully_added'));
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

        return view('cp.links.show', compact('link'))->with('title', __('interface.admin.links.show_title'));
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

        return view('cp.links.create_edit', compact('row', 'options'))->with('title', __('interface.admin.links.edit_title'));
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

        return redirect(URL::route('cp.links.index'))->with('success', __('interface.messages.data_updated'));
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

        return view('cp.links.import', compact('maxUploadFileSize'))->with('title', __('interface.admin.links.import_title'));
    }

    /**
     * Импортирует ссылки из загруженного файла.
     *
     * @param ImportLinksRequest $request
     * @return RedirectResponse
     */
    public function importLink(ImportLinksRequest $request): RedirectResponse
    {
        try {
            $count = 0;

            if ($request->hasFile('file')) {
                $count += $this->importExport->import($request->file('file'));
            }

            if ($request->hasFile('archive')) {
                $count += $this->importExport->importArchive($request->file('archive'));
            }
        } catch (Throwable $exception) {
            Log::error('Links import failed.', [
                'message' => $exception->getMessage(),
                'file' => $request->file('file')?->getClientOriginalName(),
                'archive' => $request->file('archive')?->getClientOriginalName(),
            ]);

            return redirect(URL::route('cp.links.import'))->with('error', __('interface.messages.import_failed'));
        }

        return redirect(URL::route('cp.links.import'))->with('success', __('interface.messages.import_completed', ['count' => $count]));
    }

    /**
     * Показывает форму экспорта ссылок.
     */
    public function export(): View
    {
        $options = $this->service->catalogOptions();

        return view('cp.links.export', compact('options'))->with('title', __('interface.admin.links.export_title'));
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

        return redirect(URL::route('cp.links.index'))->with('success', __('interface.messages.data_updated'));
    }
}
