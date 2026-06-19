<?php

namespace App\Http\Controllers;

use App\DTO\Frontend\FeedbackMessageData;
use App\DTO\Frontend\LinkSubmissionData;
use App\Http\Requests\Frontend\StoreFeedbackRequest;
use App\Http\Requests\Frontend\StoreLinkRequest;
use App\Services\Frontend\FrontendService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function __construct(private readonly FrontendService $frontend) {}

    /**
     * Показывает главную страницу каталога.
     */
    public function index(): View
    {
        return view('frontend.index', $this->frontend->homePage());
    }

    /**
     * Показывает раздел каталога с вложенными разделами и ссылками.
     */
    public function catalog(int $id = 0): View
    {
        return view('frontend.index', $this->frontend->catalogPage($id));
    }

    /**
     * Показывает детальную страницу сайта.
     */
    public function info(int $id): View
    {
        return view('frontend.info', $this->frontend->linkInfo($id));
    }

    /**
     * Показывает форму добавления сайта в каталог.
     */
    public function addurl(): View
    {
        return view('frontend.addurl', [
            'options' => $this->frontend->catalogOptions(),
            'title' => __('interface.frontend.add_site_title'),
        ]);
    }

    /**
     * Принимает заявку на добавление сайта.
     */
    public function add(StoreLinkRequest $request): RedirectResponse
    {
        $message = $this->frontend->submitLink(LinkSubmissionData::fromArray($request->validated()));

        return redirect()->route('addurl')->with('success', $message);
    }

    /**
     * Учитывает переход и перенаправляет пользователя на внешний сайт.
     */
    public function redirect(int $id): RedirectResponse
    {
        return redirect()->away($this->frontend->redirectUrl($id));
    }

    /**
     * Показывает страницу правил каталога.
     */
    public function rules(): View
    {
        return view('frontend.rules')->with('title', __('interface.frontend.rules_title'));
    }

    /**
     * Показывает страницу обратной связи.
     */
    public function contact(): View
    {
        return view('frontend.contact')->with('title', __('interface.frontend.contact_title'));
    }

    /**
     * Отправляет сообщение из формы обратной связи администрации.
     */
    public function sendMsg(StoreFeedbackRequest $request): RedirectResponse
    {
        $this->frontend->sendFeedback(
            FeedbackMessageData::fromArray($request->validated(), $request->ip())
        );

        return redirect()->route('contact')->with('success', __('interface.frontend.message_sent'));
    }

    /**
     * Формирует основной XML sitemap.
     */
    public function sitemap(): Response
    {
        return response()
            ->view('frontend.sitemap', $this->frontend->sitemapData())
            ->header('Content-type', 'text/xml');
    }

    /**
     * Формирует страницу XML sitemap со ссылками.
     */
    public function maplinks(int $page = 1): Response
    {
        return response()
            ->view('frontend.maplinks', ['links' => $this->frontend->linksMap($page)])
            ->header('Content-type', 'text/xml');
    }

    /**
     * Формирует страницу XML sitemap с разделами каталога.
     */
    public function mapcatalogs(int $page = 1): Response
    {
        return response()
            ->view('frontend.mapcatalogs', ['catalogs' => $this->frontend->catalogsMap($page)])
            ->header('Content-type', 'text/xml');
    }
}
