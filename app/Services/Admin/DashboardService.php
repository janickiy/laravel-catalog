<?php

namespace App\Services\Admin;

use App\Enums\LinkStatus;
use App\Repositories\AdminRepository;
use App\Repositories\CatalogRepository;
use App\Repositories\FeedbackRepository;
use App\Repositories\LinksRepository;
use App\Repositories\SettingsRepository;

class DashboardService
{
    public function __construct(
        private readonly LinksRepository $links,
        private readonly CatalogRepository $catalogs,
        private readonly FeedbackRepository $feedback,
        private readonly AdminRepository $admins,
        private readonly SettingsRepository $settings,
    ) {}

    /**
     * Собирает все данные для dashboard административной панели.
     */
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

    /**
     * Формирует карточки со сводными показателями dashboard.
     */
    private function summaryCards(array $linkStatuses): array
    {
        return [
            [
                'label' => __('interface.admin.dashboard_cards.total_links'),
                'value' => $this->links->countAll(),
                'icon' => 'bi-link-45deg',
                'variant' => 'primary',
                'url' => route('cp.links.index'),
            ],
            [
                'label' => __('interface.admin.dashboard_cards.categories'),
                'value' => $this->catalogs->countAll(),
                'icon' => 'bi-list-ul',
                'variant' => 'success',
                'url' => route('cp.catalog.index'),
            ],
            [
                'label' => __('interface.admin.dashboard_cards.messages'),
                'value' => $this->feedback->countAll(),
                'icon' => 'bi-envelope',
                'variant' => 'warning',
                'url' => route('cp.feedback.index'),
            ],
            [
                'label' => __('interface.admin.dashboard_cards.administrators'),
                'value' => $this->admins->countAll(),
                'icon' => 'bi-people',
                'variant' => 'info',
                'url' => route('cp.admin.index'),
            ],
            [
                'label' => __('interface.admin.dashboard_cards.settings'),
                'value' => $this->settings->countAll(),
                'icon' => 'bi-gear',
                'variant' => 'secondary',
                'url' => route('cp.settings.index'),
            ],
            [
                'label' => __('interface.admin.dashboard_cards.pending'),
                'value' => $linkStatuses['pending']['count'],
                'icon' => 'bi-hourglass-split',
                'colorClass' => LinkStatus::Pending->cssColor(),
                'url' => route('cp.links.index'),
            ],
        ];
    }

    /**
     * Собирает статистику ссылок по статусам.
     */
    private function linkStatuses(): array
    {
        $total = max(1, $this->links->countAll());

        return [
            'pending' => $this->statusStat(LinkStatus::Pending, $total),
            'published' => $this->statusStat(LinkStatus::Published, $total),
            'blocked' => $this->statusStat(LinkStatus::Blocked, $total),
        ];
    }

    /**
     * Рассчитывает количество и процент ссылок для одного статуса.
     */
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

    /**
     * Возвращает список быстрых действий dashboard.
     */
    private function quickActions(): array
    {
        return [
            [
                'label' => __('interface.admin.quick_actions.add_link'),
                'url' => route('cp.links.create'),
                'icon' => 'bi-plus-circle',
            ],
            [
                'label' => __('interface.admin.quick_actions.import_links'),
                'url' => route('cp.links.import'),
                'icon' => 'bi-download',
            ],
            [
                'label' => __('interface.admin.quick_actions.export_links'),
                'url' => route('cp.links.export'),
                'icon' => 'bi-upload',
            ],
            [
                'label' => __('interface.admin.quick_actions.add_category'),
                'url' => route('cp.catalog.create'),
                'icon' => 'bi-folder-plus',
            ],
        ];
    }
}
