<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\V2\RealEstate\PropertyController;
use App\Http\Controllers\Backend\V2\RealEstate\PropertyFacilityController;
use App\Http\Controllers\Backend\V2\RealEstate\FloorplanController;
use App\Http\Controllers\Backend\V2\RealEstate\FloorplanRoomController;
use App\Http\Controllers\Backend\V2\RealEstate\GalleryController;
use App\Http\Controllers\Backend\V2\RealEstate\GalleryCatalogueController;
use App\Http\Controllers\Backend\V2\RealEstate\LocationHighlightController;
use App\Http\Controllers\Backend\V2\RealEstate\AgentController;
use App\Http\Controllers\Backend\V2\RealEstate\VisitRequestController;

Route::group(['middleware' => ['admin', 'locale', 'backend_default_locale'], 'as' => ''], function () {
    // Property
    Route::get('property/index', [PropertyController::class, 'index'])->name('property.index');
    Route::get('property/create', [PropertyController::class, 'create'])->name('property.create');
    Route::post('property/store', [PropertyController::class, 'store'])->name('property.store');
    Route::get('property/{id}/edit', [PropertyController::class, 'edit'])->where(['id' => '[0-9]+'])->name('property.edit');
    Route::post('property/{id}/update', [PropertyController::class, 'update'])->where(['id' => '[0-9]+'])->name('property.update');
    Route::get('property/{id}/delete', [PropertyController::class, 'delete'])->where(['id' => '[0-9]+'])->name('property.delete');
    Route::delete('property/{id}/destroy', [PropertyController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('property.destroy');

    // PropertyFacility
    Route::get('property_facility/index', [PropertyFacilityController::class, 'index'])->name('property_facility.index');
    Route::get('property_facility/create', [PropertyFacilityController::class, 'create'])->name('property_facility.create');
    Route::post('property_facility/store', [PropertyFacilityController::class, 'store'])->name('property_facility.store');
    Route::get('property_facility/{id}/edit', [PropertyFacilityController::class, 'edit'])->where(['id' => '[0-9]+'])->name('property_facility.edit');
    Route::post('property_facility/{id}/update', [PropertyFacilityController::class, 'update'])->where(['id' => '[0-9]+'])->name('property_facility.update');
    Route::get('property_facility/{id}/delete', [PropertyFacilityController::class, 'delete'])->where(['id' => '[0-9]+'])->name('property_facility.delete');
    Route::delete('property_facility/{id}/destroy', [PropertyFacilityController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('property_facility.destroy');

    // Floorplan
    Route::get('floorplan/index', [FloorplanController::class, 'index'])->name('floorplan.index');
    Route::get('floorplan/create', [FloorplanController::class, 'create'])->name('floorplan.create');
    Route::post('floorplan/store', [FloorplanController::class, 'store'])->name('floorplan.store');
    Route::get('floorplan/{id}/edit', [FloorplanController::class, 'edit'])->where(['id' => '[0-9]+'])->name('floorplan.edit');
    Route::post('floorplan/{id}/update', [FloorplanController::class, 'update'])->where(['id' => '[0-9]+'])->name('floorplan.update');
    Route::get('floorplan/{id}/delete', [FloorplanController::class, 'delete'])->where(['id' => '[0-9]+'])->name('floorplan.delete');
    Route::delete('floorplan/{id}/destroy', [FloorplanController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('floorplan.destroy');

    // FloorplanRoom
    Route::get('floorplan_room/index', [FloorplanRoomController::class, 'index'])->name('floorplan_room.index');
    Route::get('floorplan_room/create', [FloorplanRoomController::class, 'create'])->name('floorplan_room.create');
    Route::post('floorplan_room/store', [FloorplanRoomController::class, 'store'])->name('floorplan_room.store');
    Route::get('floorplan_room/{id}/edit', [FloorplanRoomController::class, 'edit'])->where(['id' => '[0-9]+'])->name('floorplan_room.edit');
    Route::post('floorplan_room/{id}/update', [FloorplanRoomController::class, 'update'])->where(['id' => '[0-9]+'])->name('floorplan_room.update');
    Route::get('floorplan_room/{id}/delete', [FloorplanRoomController::class, 'delete'])->where(['id' => '[0-9]+'])->name('floorplan_room.delete');
    Route::delete('floorplan_room/{id}/destroy', [FloorplanRoomController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('floorplan_room.destroy');

    // Gallery
    Route::get('gallery/index', [GalleryController::class, 'index'])->name('gallery.index');
    Route::get('gallery/create', [GalleryController::class, 'create'])->name('gallery.create');
    Route::post('gallery/store', [GalleryController::class, 'store'])->name('gallery.store');
    Route::get('gallery/{id}/edit', [GalleryController::class, 'edit'])->where(['id' => '[0-9]+'])->name('gallery.edit');
    Route::post('gallery/{id}/update', [GalleryController::class, 'update'])->where(['id' => '[0-9]+'])->name('gallery.update');
    Route::get('gallery/{id}/delete', [GalleryController::class, 'delete'])->where(['id' => '[0-9]+'])->name('gallery.delete');
    Route::delete('gallery/{id}/destroy', [GalleryController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('gallery.destroy');

    // GalleryCatalogue
    Route::group(['prefix' => 'gallery/catalogue'], function () {
        Route::get('index', [GalleryCatalogueController::class, 'index'])->name('gallery.catalogue.index');
        Route::get('create', [GalleryCatalogueController::class, 'create'])->name('gallery.catalogue.create');
        Route::post('store', [GalleryCatalogueController::class, 'store'])->name('gallery.catalogue.store');
        Route::get('{id}/edit', [GalleryCatalogueController::class, 'edit'])->where(['id' => '[0-9]+'])->name('gallery.catalogue.edit');
        Route::post('{id}/update', [GalleryCatalogueController::class, 'update'])->where(['id' => '[0-9]+'])->name('gallery.catalogue.update');
        Route::get('{id}/delete', [GalleryCatalogueController::class, 'delete'])->where(['id' => '[0-9]+'])->name('gallery.catalogue.delete');
        Route::delete('{id}/destroy', [GalleryCatalogueController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('gallery.catalogue.destroy');
    });

    // LocationHighlight
    Route::get('location_highlight/index', [LocationHighlightController::class, 'index'])->name('location_highlight.index');
    Route::get('location_highlight/create', [LocationHighlightController::class, 'create'])->name('location_highlight.create');
    Route::post('location_highlight/store', [LocationHighlightController::class, 'store'])->name('location_highlight.store');
    Route::get('location_highlight/{id}/edit', [LocationHighlightController::class, 'edit'])->where(['id' => '[0-9]+'])->name('location_highlight.edit');
    Route::post('location_highlight/{id}/update', [LocationHighlightController::class, 'update'])->where(['id' => '[0-9]+'])->name('location_highlight.update');
    Route::get('location_highlight/{id}/delete', [LocationHighlightController::class, 'delete'])->where(['id' => '[0-9]+'])->name('location_highlight.delete');
    Route::delete('location_highlight/{id}/destroy', [LocationHighlightController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('location_highlight.destroy');

    // Agent
    Route::get('agent/index', [AgentController::class, 'index'])->name('agent.index');
    Route::get('agent/create', [AgentController::class, 'create'])->name('agent.create');
    Route::post('agent/store', [AgentController::class, 'store'])->name('agent.store');
    Route::get('agent/{id}/edit', [AgentController::class, 'edit'])->where(['id' => '[0-9]+'])->name('agent.edit');
    Route::post('agent/{id}/update', [AgentController::class, 'update'])->where(['id' => '[0-9]+'])->name('agent.update');
    Route::get('agent/{id}/delete', [AgentController::class, 'delete'])->where(['id' => '[0-9]+'])->name('agent.delete');
    Route::delete('agent/{id}/destroy', [AgentController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('agent.destroy');

    // VisitRequest
    Route::get('visit_request/index', [VisitRequestController::class, 'index'])->name('visit_request.index');
    Route::get('visit_request/create', [VisitRequestController::class, 'create'])->name('visit_request.create');
    Route::post('visit_request/store', [VisitRequestController::class, 'store'])->name('visit_request.store');
    Route::get('visit_request/{id}/edit', [VisitRequestController::class, 'edit'])->where(['id' => '[0-9]+'])->name('visit_request.edit');
    Route::post('visit_request/{id}/update', [VisitRequestController::class, 'update'])->where(['id' => '[0-9]+'])->name('visit_request.update');
    Route::get('visit_request/{id}/delete', [VisitRequestController::class, 'delete'])->where(['id' => '[0-9]+'])->name('visit_request.delete');
    Route::delete('visit_request/{id}/destroy', [VisitRequestController::class, 'destroy'])->where(['id' => '[0-9]+'])->name('visit_request.destroy');
});
