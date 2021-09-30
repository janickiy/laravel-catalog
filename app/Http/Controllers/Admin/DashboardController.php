<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\{Links, Catalog};

class DashboardController extends Controller
{
    public function index()
    {
        $new = Links::where('status', 0)->count();
        $publish = Links::where('status', 1)->count();
        $black = Links::where('status', 2)->count();

        return view('cp.dashboard.index', compact('new', 'publish', 'black'))->with('title', 'Главная');
    }
}
