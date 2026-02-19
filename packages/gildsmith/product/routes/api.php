<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Controllers\Attribute\AttributeCreateController;
use Gildsmith\Product\Controllers\Attribute\AttributeDeleteController;
use Gildsmith\Product\Controllers\Attribute\AttributeFindController;
use Gildsmith\Product\Controllers\Attribute\AttributeIndexController;
use Gildsmith\Product\Controllers\Attribute\AttributeRestoreController;
use Gildsmith\Product\Controllers\Attribute\AttributeTrashController;
use Gildsmith\Product\Controllers\Attribute\AttributeTrashedController;
use Gildsmith\Product\Controllers\Attribute\AttributeUpdateController;
use Gildsmith\Product\Controllers\AttributeValue\AttributeValueCreateController;
use Gildsmith\Product\Controllers\AttributeValue\AttributeValueDeleteController;
use Gildsmith\Product\Controllers\AttributeValue\AttributeValueFindController;
use Gildsmith\Product\Controllers\AttributeValue\AttributeValueIndexController;
use Gildsmith\Product\Controllers\AttributeValue\AttributeValueRestoreController;
use Gildsmith\Product\Controllers\AttributeValue\AttributeValueTrashController;
use Gildsmith\Product\Controllers\AttributeValue\AttributeValueTrashedController;
use Gildsmith\Product\Controllers\AttributeValue\AttributeValueUpdateController;
use Gildsmith\Product\Controllers\Blueprint\BlueprintCreateController;
use Gildsmith\Product\Controllers\Blueprint\BlueprintDeleteController;
use Gildsmith\Product\Controllers\Blueprint\BlueprintFindController;
use Gildsmith\Product\Controllers\Blueprint\BlueprintIndexController;
use Gildsmith\Product\Controllers\Blueprint\BlueprintRestoreController;
use Gildsmith\Product\Controllers\Blueprint\BlueprintTrashController;
use Gildsmith\Product\Controllers\Blueprint\BlueprintTrashedController;
use Gildsmith\Product\Controllers\Blueprint\BlueprintUpdateController;
use Gildsmith\Product\Controllers\Product\ProductCreateController;
use Gildsmith\Product\Controllers\Product\ProductDeleteController;
use Gildsmith\Product\Controllers\Product\ProductFindController;
use Gildsmith\Product\Controllers\Product\ProductIndexController;
use Gildsmith\Product\Controllers\Product\ProductRestoreController;
use Gildsmith\Product\Controllers\Product\ProductTrashController;
use Gildsmith\Product\Controllers\Product\ProductTrashedController;
use Gildsmith\Product\Controllers\Product\ProductUpdateController;
use Gildsmith\Product\Controllers\ProductCollection\ProductCollectionCreateController;
use Gildsmith\Product\Controllers\ProductCollection\ProductCollectionDeleteController;
use Gildsmith\Product\Controllers\ProductCollection\ProductCollectionFindController;
use Gildsmith\Product\Controllers\ProductCollection\ProductCollectionIndexController;
use Gildsmith\Product\Controllers\ProductCollection\ProductCollectionRestoreController;
use Gildsmith\Product\Controllers\ProductCollection\ProductCollectionTrashController;
use Gildsmith\Product\Controllers\ProductCollection\ProductCollectionTrashedController;
use Gildsmith\Product\Controllers\ProductCollection\ProductCollectionUpdateController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::get('/', ProductIndexController::class)->can('viewAny', ProductInterface::class);
    Route::post('/', ProductCreateController::class)->can('create', ProductInterface::class);
    Route::get('/trashed', ProductTrashedController::class)->can('viewTrashed', ProductInterface::class);
    Route::post('/{code}/trash', ProductTrashController::class)->can('trash', ProductInterface::class);
    Route::post('/{code}/restore', ProductRestoreController::class)->can('restore', ProductInterface::class);
    Route::get('/{code}', ProductFindController::class)->can('view', ProductInterface::class);
    Route::put('/{code}', ProductUpdateController::class)->can('update', ProductInterface::class);
    Route::patch('/{code}', ProductUpdateController::class)->can('update', ProductInterface::class);
    Route::delete('/{code}', ProductDeleteController::class)->can('delete', ProductInterface::class);
});

Route::prefix('attributes')->group(function () {
    Route::get('/', AttributeIndexController::class)->can('viewAny', AttributeInterface::class);
    Route::post('/', AttributeCreateController::class)->can('create', AttributeInterface::class);
    Route::get('/trashed', AttributeTrashedController::class)->can('viewTrashed', AttributeInterface::class);
    Route::post('/{attribute}/trash', AttributeTrashController::class)->can('trash', AttributeInterface::class);
    Route::post('/{attribute}/restore', AttributeRestoreController::class)->can('restore', AttributeInterface::class);
    Route::get('/{attribute}', AttributeFindController::class)->can('view', AttributeInterface::class);
    Route::put('/{attribute}', AttributeUpdateController::class)->can('update', AttributeInterface::class);
    Route::patch('/{attribute}', AttributeUpdateController::class)->can('update', AttributeInterface::class);
    Route::delete('/{attribute}', AttributeDeleteController::class)->can('delete', AttributeInterface::class);

    Route::prefix('{attribute}/values')->group(function () {
        Route::get('/', AttributeValueIndexController::class)->can('viewAny', AttributeValueInterface::class);
        Route::post('/', AttributeValueCreateController::class)->can('create', AttributeValueInterface::class);
        Route::get('/trashed', AttributeValueTrashedController::class)->can('viewTrashed', AttributeValueInterface::class);
        Route::post('/{value}/trash', AttributeValueTrashController::class)->can('trash', AttributeValueInterface::class);
        Route::post('/{value}/restore', AttributeValueRestoreController::class)->can('restore', AttributeValueInterface::class);
        Route::get('/{value}', AttributeValueFindController::class)->can('view', AttributeValueInterface::class);
        Route::put('/{value}', AttributeValueUpdateController::class)->can('update', AttributeValueInterface::class);
        Route::patch('/{value}', AttributeValueUpdateController::class)->can('update', AttributeValueInterface::class);
        Route::delete('/{value}', AttributeValueDeleteController::class)->can('delete', AttributeValueInterface::class);
    });
});

Route::prefix('blueprints')->group(function () {
    Route::get('/', BlueprintIndexController::class)->can('viewAny', BlueprintInterface::class);
    Route::post('/', BlueprintCreateController::class)->can('create', BlueprintInterface::class);
    Route::get('/trashed', BlueprintTrashedController::class)->can('viewTrashed', BlueprintInterface::class);
    Route::post('/{code}/trash', BlueprintTrashController::class)->can('trash', BlueprintInterface::class);
    Route::post('/{code}/restore', BlueprintRestoreController::class)->can('restore', BlueprintInterface::class);
    Route::get('/{code}', BlueprintFindController::class)->can('view', BlueprintInterface::class);
    Route::put('/{code}', BlueprintUpdateController::class)->can('update', BlueprintInterface::class);
    Route::patch('/{code}', BlueprintUpdateController::class)->can('update', BlueprintInterface::class);
    Route::delete('/{code}', BlueprintDeleteController::class)->can('delete', BlueprintInterface::class);
});

Route::prefix('collections')->group(function () {
    Route::get('/', ProductCollectionIndexController::class)->can('viewAny', ProductCollectionInterface::class);
    Route::post('/', ProductCollectionCreateController::class)->can('create', ProductCollectionInterface::class);
    Route::get('/trashed', ProductCollectionTrashedController::class)->can('viewTrashed', ProductCollectionInterface::class);
    Route::post('/{code}/trash', ProductCollectionTrashController::class)->can('trash', ProductCollectionInterface::class);
    Route::post('/{code}/restore', ProductCollectionRestoreController::class)->can('restore', ProductCollectionInterface::class);
    Route::get('/{code}', ProductCollectionFindController::class)->can('view', ProductCollectionInterface::class);
    Route::put('/{code}', ProductCollectionUpdateController::class)->can('update', ProductCollectionInterface::class);
    Route::patch('/{code}', ProductCollectionUpdateController::class)->can('update', ProductCollectionInterface::class);
    Route::delete('/{code}', ProductCollectionDeleteController::class)->can('delete', ProductCollectionInterface::class);
});
