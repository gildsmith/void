<?php

declare(strict_types=1);

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

Route::prefix('products')->group(function () {
    Route::get('/', ProductIndexController::class);
    Route::post('/', ProductCreateController::class);
    Route::get('/trashed', ProductTrashedController::class);
    Route::post('/{code}/trash', ProductTrashController::class);
    Route::post('/{code}/restore', ProductRestoreController::class);
    Route::get('/{code}', ProductFindController::class);
    Route::put('/{code}', ProductUpdateController::class);
    Route::patch('/{code}', ProductUpdateController::class);
    Route::delete('/{code}', ProductDeleteController::class);
});

Route::prefix('attributes')->group(function () {
    Route::get('/', AttributeIndexController::class);
    Route::post('/', AttributeCreateController::class);
    Route::get('/trashed', AttributeTrashedController::class);
    Route::post('/{attribute}/trash', AttributeTrashController::class);
    Route::post('/{attribute}/restore', AttributeRestoreController::class);
    Route::get('/{attribute}', AttributeFindController::class);
    Route::put('/{attribute}', AttributeUpdateController::class);
    Route::patch('/{attribute}', AttributeUpdateController::class);
    Route::delete('/{attribute}', AttributeDeleteController::class);

    Route::prefix('{attribute}/values')->group(function () {
        Route::get('/', AttributeValueIndexController::class);
        Route::post('/', AttributeValueCreateController::class);
        Route::get('/trashed', AttributeValueTrashedController::class);
        Route::post('/{value}/trash', AttributeValueTrashController::class);
        Route::post('/{value}/restore', AttributeValueRestoreController::class);
        Route::get('/{value}', AttributeValueFindController::class);
        Route::put('/{value}', AttributeValueUpdateController::class);
        Route::patch('/{value}', AttributeValueUpdateController::class);
        Route::delete('/{value}', AttributeValueDeleteController::class);
    });
});

Route::prefix('blueprints')->group(function () {
    Route::get('/', BlueprintIndexController::class);
    Route::post('/', BlueprintCreateController::class);
    Route::get('/trashed', BlueprintTrashedController::class);
    Route::post('/{code}/trash', BlueprintTrashController::class);
    Route::post('/{code}/restore', BlueprintRestoreController::class);
    Route::get('/{code}', BlueprintFindController::class);
    Route::put('/{code}', BlueprintUpdateController::class);
    Route::patch('/{code}', BlueprintUpdateController::class);
    Route::delete('/{code}', BlueprintDeleteController::class);
});

Route::prefix('collections')->group(function () {
    Route::get('/', ProductCollectionIndexController::class);
    Route::post('/', ProductCollectionCreateController::class);
    Route::get('/trashed', ProductCollectionTrashedController::class);
    Route::post('/{code}/trash', ProductCollectionTrashController::class);
    Route::post('/{code}/restore', ProductCollectionRestoreController::class);
    Route::get('/{code}', ProductCollectionFindController::class);
    Route::put('/{code}', ProductCollectionUpdateController::class);
    Route::patch('/{code}', ProductCollectionUpdateController::class);
    Route::delete('/{code}', ProductCollectionDeleteController::class);
});
