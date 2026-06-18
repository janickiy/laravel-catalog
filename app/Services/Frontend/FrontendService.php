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
    ) {
    }

    public function homePage(): array
    {
        [$arr, $number] = $this->catalogGrid($this->catalogs->childrenWithLinkCounts(null), true);

        return [
            'title' => 'Главная страница',
            'description' => 'Белый каталог сайтов',
            'keywords' => 'белый каталог сайтов, добавить сайт',
            'arr' => $arr,
            'number' => $number,
            'links' => $this->links->latestPublished(5),
            'rank' => 1,
        ];
    }

    public function catalogPage(int $id): array
    {
        $title = 'Разное';
        $description = 'Белый каталог сайтов';
        $keywords = 'белый каталог сайтов, добавить сайт';
        $catalogName = 'Разное';
        $paginator = null;
        $arr = [];
        $number = 0;

        if ($id > 0) {
            $catalog = $this->catalogs->find($id);

            if (!$catalog instanceof Catalog) {
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
            $pathway = '<a href="' . URL::route('index') . '">Главная</a>» Разное';
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

    public function linkInfo(int $id): array
    {
        $link = $this->links->findPublished($id);

        if (!$link instanceof Links) {
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

    public function catalogOptions(): array
    {
        return $this->catalogs->options([0 => '-Разное']);
    }

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
            ? 'Сайт добавлен в каталог'
            : 'Сайт добавлен в каталог и после проверки будет доступен в каталоге';
    }

    public function redirectUrl(int $id): string
    {
        $link = $this->links->findAny($id);

        if (!$link instanceof Links) {
            abort(404);
        }

        return StringHelper::urlWithPrefix($link->url);
    }

    public function sendFeedback(FeedbackMessageData $data): void
    {
        $this->feedback->createFromData($data);

        event(new FeedbackMailEvent($data->toEventPayload()));
    }

    public function sitemapData(): array
    {
        return [
            'l' => intdiv(max(0, $this->links->countPublished() - 1), Links::PER_PAGE) + 1,
            'c' => intdiv(max(0, $this->catalogs->countAll() - 1), Catalog::PER_PAGE) + 1,
        ];
    }

    public function linksMap(int $page): Collection
    {
        return $this->links->sitemapPage($page);
    }

    public function catalogsMap(int $page): Collection
    {
        return $this->catalogs->sitemapPage($page);
    }

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
            $items[] = ['Разное', 0, null, 0, ''];
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

    private function pathway(int $id): string
    {
        $pathway = '<a href="' . URL::route('index') . '">Главная</a>';

        foreach ($this->catalogs->pathToRoot($id) as [$catalogId, $catalogName]) {
            if ((int) $catalogId === $id) {
                $pathway .= '» ' . e($catalogName);
            } else {
                $pathway .= '» <a href="' . URL::route('catalog', ['id' => $catalogId]) . '">' . e($catalogName) . '</a>';
            }
        }

        return $pathway;
    }

    private function subCategoryLinks(int $id): string
    {
        return $this->catalogs->childrenWithLinkCounts($id)
            ->map(fn ($catalog) => '<a href="' . URL::route('catalog', ['id' => $catalog->id]) . '">' . e($catalog->name) . '</a> <span>(' . (int) $catalog->number_links . ')</span>')
            ->implode(', ');
    }
}
