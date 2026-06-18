<?php

namespace App\Http\Controllers\Admin;

class Controller extends \App\Http\Controllers\Controller
{
    /**
     * Подключает проверку авторизации администратора для всех cp-контроллеров.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
}
