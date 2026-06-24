<?php

namespace App\Http\Controllers\Admin;

class Controller extends \App\Http\Controllers\Controller
{
    /**
     * Attaches administrator authorization checks to all control panel controllers.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
}
