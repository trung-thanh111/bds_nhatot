<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V1\RealEstate\ProjectTypeController;

Route::group(['middleware' => ['admin', 'locale', 'backend_default_locale'], 'as' => ''], function () {
    Route::group(['prefix' => 'project/type'], function () {
        Route::get('index', [ProjectTypeController::class, 'index'])->name('realestate.project_type.index');
        Route::get('create', [ProjectTypeController::class, 'create'])->name('realestate.project_type.create');
        Route::post('store', [ProjectTypeController::class, 'store'])->name('realestate.project_type.store');
        Route::get('{id}/edit', [ProjectTypeController::class, 'edit'])->where(['id' => '[0-9]+'])->name('realestate.project_type.edit');
        Route::post('{id}/update', [ProjectTypeController::class, 'update'])->where(['id' => '[0-9]+'])->name('realestate.project_type.update');
        Route::get('{id}/delete', [ProjectTypeController::class, 'delete'])->where(['id' => '[0-9]+'])->name('realestate.project_type.delete');
        Route::delete('{id}/destroy', [ProjectTypeController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('realestate.project_type.destroy');
    });
});
