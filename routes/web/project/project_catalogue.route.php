<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V1\RealEstate\ProjectCatalogueController;

Route::group(['middleware' => ['admin', 'locale', 'backend_default_locale'], 'as' => ''], function () {
    Route::group(['prefix' => 'project/catalogue'], function () {
        Route::get('index', [ProjectCatalogueController::class, 'index'])->name('realestate.project_catalogue.index');
        Route::get('create', [ProjectCatalogueController::class, 'create'])->name('realestate.project_catalogue.create');
        Route::post('store', [ProjectCatalogueController::class, 'store'])->name('realestate.project_catalogue.store');
        Route::get('{id}/edit', [ProjectCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('realestate.project_catalogue.edit');
        Route::post('{id}/update', [ProjectCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('realestate.project_catalogue.update');
        Route::get('{id}/delete', [ProjectCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('realestate.project_catalogue.delete');
        Route::delete('{id}/destroy', [ProjectCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('realestate.project_catalogue.destroy');
    });
});
