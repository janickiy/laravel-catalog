<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    DashboardController,
    AdminController,
    DataTableController,
    CatalogController,
    LinksController,
    FeedbackController,
    SettingsController
};

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

Route::group(['prefix' => 'cp'], function () {
    Route::get('', [DashboardController::class, 'index'])->name('cp.dashbaord.index');

    Route::group(['prefix' => 'links'], function () {
        Route::get('', [LinksController::class, 'index'])->name('cp.links.index');
        Route::get('create', [LinksController::class, 'create'])->name('cp.links.create');
        Route::post('store', [LinksController::class, 'store'])->name('cp.links.store');
        Route::get('show/{id}', [LinksController::class, 'show'])->name('cp.links.show')->where('id', '[0-9]+');
        Route::get('edit/{id}', [LinksController::class, 'edit'])->name('cp.links.edit')->where('id', '[0-9]+');
        Route::put('update', [LinksController::class, 'update'])->name('cp.links.update');
        Route::delete('destroy/{id}', [LinksController::class, 'destroy'])->name('cp.links.destroy')->where('id', '[0-9]+');
        Route::get('import', [LinksController::class, 'importForm'])->name('cp.links.import');
        Route::post('importLink', [LinksController::class, 'importLink'])->name('cp.links.importlink');
        Route::get('export', [LinksController::class, 'export'])->name('cp.links.export');
        Route::post('exportLink', [LinksController::class, 'exportLink'])->name('cp.links.export_link');
        Route::put('status-links', [LinksController::class, 'statusLinks'])->name('cp.statuslinks.update');
    });

    Route::group(['prefix' => 'catalog'], function () {
        Route::get('',[CatalogController::class, 'index'])->name('cp.catalog.index');
        Route::get('create/{parent_id?}',[CatalogController::class, 'create'])->name('cp.catalog.create')->where('parent_id', '[0-9]+');
        Route::post('store',[CatalogController::class, 'store'])->name('cp.catalog.store');
        Route::get('edit/{id}',[CatalogController::class, 'edit'])->name('cp.catalog.edit')->where('id', '[0-9]+');
        Route::put('update',[CatalogController::class, 'update'])->name('cp.catalog.update');
        Route::get('delete/{id}',[CatalogController::class, 'delete'])->name('cp.catalog.delete')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'feedback'], function () {
        Route::get('', [FeedbackController::class, 'index'])->name('cp.feedback.index');
        Route::get('show/{id}', [FeedbackController::class, 'show'])->name('cp.feedback.show')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'admins'], function () {
        Route::get('', [AdminController::class, 'index'])->name('cp.admin.index');
        Route::get('create', [AdminController::class, 'create'])->name('cp.admin.create');
        Route::post('create', [AdminController::class, 'store'])->name('cp.admin.store');
        Route::get('edit/{id}', [AdminController::class, 'edit'])->name('cp.admin.edit')->where('id', '[0-9]+');
        Route::put('update', [AdminController::class, 'update'])->name('cp.admin.update');
        Route::delete('destroy/{id}', [AdminController::class, 'destroy'])->name('cp.admin.destroy')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('', [SettingsController::class, 'index'])->name('cp.settings.index');
        Route::get('create', [SettingsController::class, 'create'])->name('cp.settings.create');
        Route::post('create', [SettingsController::class, 'store'])->name('cp.settings.store');
        Route::get('edit/{id}', [SettingsController::class, 'edit'])->name('cp.settings.edit')->where('id', '[0-9]+');
        Route::put('update', [SettingsController::class, 'update'])->name('cp.settings.update');
        Route::delete('destroy/{id}', [SettingsController::class, 'destroy'])->name('cp.settings.destroy')->where('id', '[0-9]+');
    });

    Route::group(['prefix' => 'datatable'], function () {
        Route::any('links', [DataTableController::class, 'getLinks'])->name('cp.datatable.links');
        Route::any('admin', [DataTableController::class, 'getAdmin'])->name('cp.datatable.admin');
        Route::any('feedback', [DataTableController::class, 'getFeedback'])->name('cp.datatable.feedback');
        Route::any('settings', [DataTableController::class, 'getSettings'])->name('cp.datatable.settings');
    });

});




