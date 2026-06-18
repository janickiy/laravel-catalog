<?php

namespace App\Services\Admin;

use App\Enums\LinkStatus;
use App\Repositories\AdminRepository;
use App\Repositories\CatalogRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\LinksRepository;
use App\Repositories\SettingsRepository;
use Illuminate\Support\Facades\URL;

class DashboardService
{
    public function __construct(
        private readonly LinksRepository $links,
        private readonly CatalogRepository $catalogs,
        private readonly FeedbackRepository $feedback,
        private readonly AdminRepository $admins,
        private readonly SettingsRepository $settings,
    ) {
    }

    public function data(): array
    {
        $linkStatuses = $this->linkStatuses();

        return [
            'summaryCards' => $this->summaryCards($linkStatuses),
            'linkStatuses' => $linkStatuses,
            'quickActions' => $this->quickActions(),
            'latestLinks' => $this->links->latestForDashboard(8),
            'topViewedLinks' => $this->links->topViewed(5),
            'latestFeedback' => $this->feedback->latest(5),
            'latestAdmins' => $this->admins->latest(5),
        ];
    }

    private function summaryCards(array $linkStatuses): array
    {
        return [
            [
                'label' => 'Всего ссылок',
                'value' => $this->links->countAll(),
                'icon' => 'bi-link-45deg',
                'variant' => 'primary',
                'url' => URL::route('cp.links.index'),
            ],
            [
                'label' => 'Категории',
                'value' => $this->catalogs->countAll(),
                'icon' => 'bi-list-ul',
                'variant' => 'success',
                'url' => URL::route('cp.catalog.index'),
            ],
            [
                'label' => 'Сообщения',
                'value' => $this->feedback->countAll(),
                'icon' => 'bi-envelope',
                'variant' => 'warning',
                'url' => URL::route('cp.feedback.index'),
            ],
            [
                'label' => 'Администраторы',
                'value' => $this->admins->countAll(),
                'icon' => 'bi-people',
                'variant' => 'info',
                'url' => URL::route('cp.admin.index'),
            ],
            [
                'label' => 'Настройки',
                'value' => $this->settings->countAll(),
                'icon' => 'bi-gear',
                'variant' => 'secondary',
                'url' => URL::route('cp.settings.index'),
            ],
            [
                'label' => 'Ожидают проверки',
                'value' => $linkStatuses['pending']['count'],
                'icon' => 'bi-hourglass-split',
                'colorClass' => LinkStatus::Pending->cssColor(),
                'url' => URL::route('cp.links.index'),
            ],
        ];
    }

    private function linkStatuses(): array
    {
        $total = max(1, $this->links->countAll());

        return [
            'pending' => $this->statusStat(LinkStatus::Pending, $total),
            'published' => $this->statusStat(LinkStatus::Published, $total),
            'blocked' => $this->statusStat(LinkStatus::Blocked, $total),
        ];
    }

    private function statusStat(LinkStatus $status, int $total): array
    {
        $count = $this->links->countByStatus($status);

        return [
            'label' => $status->label(),
            'count' => $count,
            'percent' => (int) round($count / $total * 100),
            'barClass' => $status->cssColor(),
        ];
    }

    private function quickActions(): array
    {
        return [
            [
                'label' => 'Добавить ссылку',
                'url' => URL::route('cp.links.create'),
                'icon' => 'bi-plus-circle',
            ],
            [
                'label' => 'Импорт ссылок',
                'url' => URL::route('cp.links.import'),
                'icon' => 'bi-download',
            ],
            [
                'label' => 'Экспорт ссылок',
                'url' => URL::route('cp.links.export'),
                'icon' => 'bi-upload',
            ],
            [
                'label' => 'Добавить категорию',
                'url' => URL::route('cp.catalog.create'),
                'icon' => 'bi-folder-plus',
            ],
        ];
    }
}
