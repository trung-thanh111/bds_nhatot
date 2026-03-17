<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V1\RealEstate\ProjectPropertyGroupController;

Route::group(['middleware' => ['admin', 'locale', 'backend_default_locale'], 'as' => ''], function () {
    Route::group(['prefix' => 'property/group'], function () {
        Route::get('index', [ProjectPropertyGroupController::class, 'index'])->name('realestate.property_group.index');
        Route::get('create', [ProjectPropertyGroupController::class, 'create'])->name('realestate.property_group.create');
        Route::post('store', [ProjectPropertyGroupController::class, 'store'])->name('realestate.property_group.store');
        Route::get('{id}/edit', [ProjectPropertyGroupController::class, 'edit'])->where(['id' => '[0-9]+'])->name('realestate.property_group.edit');
        Route::post('{id}/update', [ProjectPropertyGroupController::class, 'update'])->where(['id' => '[0-9]+'])->name('realestate.property_group.update');
        Route::get('{id}/delete', [ProjectPropertyGroupController::class, 'delete'])->where(['id' => '[0-9]+'])->name('realestate.property_group.delete');
        Route::delete('{id}/destroy', [ProjectPropertyGroupController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('realestate.property_group.destroy');
    });
});
