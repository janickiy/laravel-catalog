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
     * Shows the catalog home page.
     */
    public function index(): View
    {
        return view('frontend.index', $this->frontend->homePage());
    }

    /**
     * Shows a catalog category with nested categories and links.
     */
    public function catalog(int $id = 0): View
    {
        return view('frontend.index', $this->frontend->catalogPage($id));
    }

    /**
     * Shows the website detail page.
     */
    public function info(int $id): View
    {
        return view('frontend.info', $this->frontend->linkInfo($id));
    }

    /**
     * Shows the website submission form.
     */
    public function addurl(): View
    {
        return view('frontend.addurl', [
            'options' => $this->frontend->catalogOptions(),
            'title' => __('interface.frontend.add_site_title'),
        ]);
    }

    /**
     * Accepts a website submission.
     */
    public function add(StoreLinkRequest $request): RedirectResponse
    {
        $message = $this->frontend->submitLink(LinkSubmissionData::fromArray($request->validated()));

        return redirect()->route('addurl')->with('success', $message);
    }

    /**
     * Records the click-through and redirects the user to the external website.
     */
    public function redirect(int $id): RedirectResponse
    {
        return redirect()->away($this->frontend->redirectUrl($id));
    }

    /**
     * Shows the catalog rules page.
     */
    public function rules(): View
    {
        return view('frontend.rules')->with('title', __('interface.frontend.rules_title'));
    }

    /**
     * Shows the feedback page.
     */
    public function contact(): View
    {
        return view('frontend.contact')->with('title', __('interface.frontend.contact_title'));
    }

    /**
     * Sends the feedback form message to the administration.
     */
    public function sendMsg(StoreFeedbackRequest $request): RedirectResponse
    {
        $this->frontend->sendFeedback(
            FeedbackMessageData::fromArray($request->validated(), $request->ip())
        );

        return redirect()->route('contact')->with('success', __('interface.frontend.message_sent'));
    }

    /**
     * Builds the main XML sitemap.
     */
    public function sitemap(): Response
    {
        return response()
            ->view('frontend.sitemap', $this->frontend->sitemapData())
            ->header('Content-type', 'text/xml');
    }

    /**
     * Builds the XML sitemap page with links.
     */
    public function maplinks(int $page = 1): Response
    {
        return response()
            ->view('frontend.maplinks', ['links' => $this->frontend->linksMap($page)])
            ->header('Content-type', 'text/xml');
    }

    /**
     * Builds the XML sitemap page with catalog categories.
     */
    public function mapcatalogs(int $page = 1): Response
    {
        return response()
            ->view('frontend.mapcatalogs', ['catalogs' => $this->frontend->catalogsMap($page)])
            ->header('Content-type', 'text/xml');
    }
}
