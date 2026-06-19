<?php

namespace App\Services\Frontend;

use App\DTO\Frontend\FeedbackMessageData;
use App\DTO\Frontend\LinkSubmissionData;
use App\Enums\LinkStatus;
use App\Events\FeedbackMailEvent;
use App\Events\NewlinkNotifyEvent;
use App\Helpers\FileHelper;
use App\Helpers\SettingsHelpers;
use App\Helpers\StringHelper;
use App\Models\Catalog;
use App\Models\Links;
use App\Repositories\CatalogRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\LinksRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class FrontendService
{
    public function __construct(
        private readonly CatalogRepository $catalogs,
        private readonly LinksRepository $links,
        private readonly FeedbackRepository $feedback,
    ) {}

    /**
     * Собирает данные главной страницы каталога.
     */
    public function homePage(): array
    {
        [$arr, $number] = $this->catalogGrid($this->catalogs->childrenWithLinkCounts(null), true);

        return [
            'title' => __('interface.frontend.home_page'),
            'description' => __('interface.frontend.home_description'),
            'keywords' => __('interface.frontend.home_keywords'),
            'arr' => $arr,
            'number' => $number,
            'links' => $this->links->latestPublished(5),
            'rank' => 1,
        ];
    }

    /**
     * Собирает данные страницы раздела каталога.
     *
     * @param int $id
     * @return array
     */
    public function catalogPage(int $id): array
    {
        $title = __('interface.common.misc');
        $description = __('interface.frontend.home_description');
        $keywords = __('interface.frontend.home_keywords');
        $catalogName = __('interface.common.misc');
        $paginator = null;
        $arr = [];
        $number = 0;

        if ($id > 0) {
            $catalog = $this->catalogs->find($id);

            if (! $catalog instanceof Catalog) {
                abort(404);
            }

            $children = $this->catalogs->childrenWithLinkCounts($id);
            [$arr, $number] = $this->catalogGrid($children, false);
            $catalogIds = $children->pluck('id')->map(fn ($catalogId) => (int) $catalogId)->all();

            $pathway = $this->pathway($id);

            if ($catalog->parent_id === null) {
                $links = $this->links->latestPublishedInCatalogs($catalogIds, 5);
                $rank = 1;
            } else {
                $links = $this->links->paginatePublishedByCatalog($id, 10);
                $rank = $links->firstItem();
                $paginator = $links->links();
            }

            $catalogName = $catalog->name;
            $title = $catalog->name;
            $description = $catalog->description ?? $description;
            $keywords = $catalog->keywords ?? $keywords;
        } else {
            $pathway = '<a href="'.route('index').'">'.e(__('interface.frontend.home')).'</a>» '.e(__('interface.common.misc'));
            $links = $this->links->paginatePublishedByCatalog(null, 10);
            $rank = $links->firstItem();
            $paginator = $links->links();
        }

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'links' => $links,
            'arr' => $arr,
            'number' => $number,
            'paginator' => $paginator,
            'pathway' => $pathway,
            'rank' => $rank,
            'catalog_name' => $catalogName,
        ];
    }

    /**
     * Собирает данные детальной страницы сайта и увеличивает просмотры.
     *
     * @param int $id
     * @return array
     */
    public function linkInfo(int $id): array
    {
        $link = $this->links->findPublished($id);

        if (! $link instanceof Links) {
            abort(404);
        }

        $similarLinks = $this->links->randomPublishedByCatalog($link->catalog_id === null ? null : (int) $link->catalog_id, 5);
        $this->links->incrementViews($link);

        return [
            'link' => $link,
            'similar_links' => $similarLinks,
            'title' => $link->name,
        ];
    }

    /**
     * Возвращает разделы каталога для формы добавления сайта.
     */
    public function catalogOptions(): array
    {
        return $this->catalogs->options([0 => '-'.__('interface.common.misc')]);
    }

    /**
     * Сохраняет заявку на добавление сайта и отправляет уведомление.
     *
     * @param LinkSubmissionData $data
     * @return string
     */
    public function submitLink(LinkSubmissionData $data): string
    {
        $result = FileHelper::getScreenShotMini($data->url(), '1024x768', '1024', 'jpg');
        $status = (int) SettingsHelpers::getSetting('ADD_LINKS_WITHOUT_CHECK') === 1
            ? LinkStatus::Published
            : LinkStatus::Pending;
        $link = $this->links->createFromData($data->toLinkData($status->value, $result['name'] ?? ''));

        if ($link instanceof Links) {
            event(new NewlinkNotifyEvent($link));
        }

        return $status === LinkStatus::Published
            ? __('interface.frontend.link_added')
            : __('interface.frontend.link_added_pending');
    }

    /**
     * Возвращает внешний URL ссылки для редиректа.
     *
     * @param int $id
     * @return string
     */
    public function redirectUrl(int $id): string
    {
        $link = $this->links->findAny($id);

        if (! $link instanceof Links) {
            abort(404);
        }

        return StringHelper::urlWithPrefix($link->url);
    }

    /**
     * Сохраняет сообщение обратной связи и отправляет email-событие.
     *
     * @param FeedbackMessageData $data
     * @return void
     */
    public function sendFeedback(FeedbackMessageData $data): void
    {
        $this->feedback->createFromData($data);

        event(new FeedbackMailEvent($data->toEventPayload()));
    }

    /**
     * Возвращает количество страниц sitemap для ссылок и разделов.
     */
    public function sitemapData(): array
    {
        return [
            'l' => intdiv(max(0, $this->links->countPublished() - 1), Links::PER_PAGE) + 1,
            'c' => intdiv(max(0, $this->catalogs->countAll() - 1), Catalog::PER_PAGE) + 1,
        ];
    }

    /**
     * Возвращает страницу ссылок для XML sitemap.
     */
    public function linksMap(int $page): Collection
    {
        return $this->links->sitemapPage($page);
    }

    /**
     * Возвращает страницу разделов для XML sitemap.
     *
     * @param int $page
     * @return Collection
     */
    public function catalogsMap(int $page): Collection
    {
        return $this->catalogs->sitemapPage($page);
    }

    /**
     * Раскладывает разделы каталога по колонкам для фронтенда.
     *
     * @param Collection $catalogs
     * @param bool $includeMisc
     * @return array
     */
    private function catalogGrid(Collection $catalogs, bool $includeMisc): array
    {
        $items = $catalogs
            ->map(fn ($row) => [
                $row->name,
                (int) $row->id,
                $row->image,
                (int) $row->number_links,
                $this->subCategoryLinks((int) $row->id),
            ])
            ->values()
            ->all();

        if ($includeMisc) {
            $items[] = [__('interface.common.misc'), 0, null, 0, ''];
        }

        $columns = max(1, (int) SettingsHelpers::getSetting('COLUMNS_NUMBER'));
        $number = (int) ceil(count($items) / $columns);

        if ($number === 0) {
            return [[], 0];
        }

        $arr = [];

        for ($i = 0; $i < $number; $i++) {
            for ($j = 0; $j < $columns; $j++) {
                if (isset($items[$j * $number + $i])) {
                    $arr[$i][$j] = $items[$j * $number + $i];
                }
            }
        }

        return [$arr, $number];
    }

    /**
     * Формирует хлебные крошки для выбранного раздела.
     *
     * @param int $id
     * @return string
     */
    private function pathway(int $id): string
    {
        $pathway = '<a href="'.URL::route('index').'">'.e(__('interface.frontend.home')).'</a>';

        foreach ($this->catalogs->pathToRoot($id) as [$catalogId, $catalogName]) {
            if ((int) $catalogId === $id) {
                $pathway .= '» '.e($catalogName);
            } else {
                $pathway .= '» <a href="'.route('catalog', ['id' => $catalogId]).'">'.e($catalogName).'</a>';
            }
        }

        return $pathway;
    }

    /**
     * Формирует HTML-список дочерних разделов с количеством ссылок.
     *
     * @param int $id
     * @return string
     */
    private function subCategoryLinks(int $id): string
    {
        return $this->catalogs->childrenWithLinkCounts($id)
            ->map(fn ($catalog) => '<a href="'.route('catalog', ['id' => $catalog->id]).'">'.e($catalog->name).'</a> <span>('.(int) $catalog->number_links.')</span>')
            ->implode(', ');
    }
}
