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
    public function __construct(private readonly FrontendService $frontend)
    {
    }

    public function index(): View
    {
        return view('frontend.index', $this->frontend->homePage());
    }

    public function catalog(int $id = 0): View
    {
        return view('frontend.index', $this->frontend->catalogPage($id));
    }

    public function info(int $id): View
    {
        return view('frontend.info', $this->frontend->linkInfo($id));
    }

    public function addurl(): View
    {
        return view('frontend.addurl', [
            'options' => $this->frontend->catalogOptions(),
            'title' => 'Добавить сайт',
        ]);
    }

    public function add(StoreLinkRequest $request): RedirectResponse
    {
        $message = $this->frontend->submitLink(LinkSubmissionData::fromArray($request->validated()));

        return redirect()->route('addurl')->with('success', $message);
    }

    public function redirect(int $id): RedirectResponse
    {
        return redirect()->away($this->frontend->redirectUrl($id));
    }

    public function rules(): View
    {
        return view('frontend.rules')->with('title', 'Правила каталога сайтов');
    }

    public function contact(): View
    {
        return view('frontend.contact')->with('title', 'Обратная связь');
    }

    public function sendMsg(StoreFeedbackRequest $request): RedirectResponse
    {
        $this->frontend->sendFeedback(
            FeedbackMessageData::fromArray($request->validated(), $request->ip())
        );

        return redirect()->route('contact')->with('success', 'Ваше сообщение успешно отправлено');
    }

    public function sitemap(): Response
    {
        return response()
            ->view('frontend.sitemap', $this->frontend->sitemapData())
            ->header('Content-type', 'text/xml');
    }

    public function maplinks(int $page = 1): Response
    {
        return response()
            ->view('frontend.maplinks', ['links' => $this->frontend->linksMap($page)])
            ->header('Content-type', 'text/xml');
    }

    public function mapcatalogs(int $page = 1): Response
    {
        return response()
            ->view('frontend.mapcatalogs', ['catalogs' => $this->frontend->catalogsMap($page)])
            ->header('Content-type', 'text/xml');
    }
}
