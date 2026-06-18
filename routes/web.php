<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FrontendController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (app()->environment('production')) {
    URL::forceScheme('https');
}

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('singin');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('', [FrontendController::class, 'index'])->name('index');

Route::get('catalog/{id?}', [FrontendController::class, 'catalog'])->name('catalog')->where('id', '[0-9]+');

Route::get('info/{id}', [FrontendController::class,'info'])->name('info')->where('id', '[0-9]+');
Route::get('addurl', [FrontendController::class,'addurl'])->name('addurl');
Route::post('add', [FrontendController::class,'add'])->name('add');
Route::get('redirect/{id}', [FrontendController::class,'redirect'])->name('redirect')->where('id', '[0-9]+');
Route::get('rules', [FrontendController::class,'rules'])->name('rules');
Route::get('contact', [FrontendController::class,'contact'])->name('contact');
Route::post('sendmsg', [FrontendController::class,'sendMsg'])->name('sendmsg');

Route::get('sitemap.xml', [FrontendController::class, 'sitemap'])->name('sitemap');
Route::get('sitemaps/maplinks{page}.xml', [FrontendController::class, 'maplinks'])->name('maplinks')->where('page', '[0-9]+');
Route::get('sitemaps/mapcatalogs{page}.xml', [FrontendController::class,'mapcatalogs'])->name('mapcatalogs')->where('page', '[0-9]+');
