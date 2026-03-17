<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V1\RealEstate\ProjectController;

Route::group(['middleware' => ['admin', 'locale', 'backend_default_locale'], 'as' => ''], function () {
    Route::group(['prefix' => 'project'], function () {
        Route::get('index', [ProjectController::class, 'index'])->name('realestate.project.index');
        Route::get('create', [ProjectController::class, 'create'])->name('realestate.project.create');
        Route::post('store', [ProjectController::class, 'store'])->name('realestate.project.store');
        Route::get('{id}/edit', [ProjectController::class, 'edit'])->where(['id' => '[0-9]+'])->name('realestate.project.edit');
        Route::post('{id}/update', [ProjectController::class, 'update'])->where(['id' => '[0-9]+'])->name('realestate.project.update');
        Route::get('{id}/delete', [ProjectController::class, 'delete'])->where(['id' => '[0-9]+'])->name('realestate.project.delete');
        Route::delete('{id}/destroy', [ProjectController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('realestate.project.destroy');
    });
});
