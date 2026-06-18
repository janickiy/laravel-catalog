<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\DataTableService;

class DataTableController extends Controller
{
    public function __construct(private readonly DataTableService $dataTables)
    {
        parent::__construct();
    }

    /**
     * Возвращает JSON-данные для таблицы администраторов.
     */
    public function getAdmin(): mixed
    {
        return $this->dataTables->admins();
    }

    /**
     * Возвращает JSON-данные для таблицы ссылок.
     */
    public function getLinks(): mixed
    {
        return $this->dataTables->links();
    }

    /**
     * Возвращает JSON-данные для таблицы сообщений обратной связи.
     */
    public function getFeedback(): mixed
    {
        return $this->dataTables->feedback();
    }

    /**
     * Возвращает JSON-данные для таблицы настроек.
     */
    public function getSettings(): mixed
    {
        return $this->dataTables->settings();
    }
}
