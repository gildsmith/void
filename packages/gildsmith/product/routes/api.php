<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Contract\Routing\ResourceAbility as Ability;
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
    Route::get('/', ProductIndexController::class)->can(Ability::ViewAny->value, ProductInterface::class);
    Route::post('/', ProductCreateController::class)->can(Ability::Create->value, ProductInterface::class);
    Route::get('/trashed', ProductTrashedController::class)->can(Ability::ViewTrashed->value, ProductInterface::class);
    Route::post('/{code}/trash', ProductTrashController::class)->can(Ability::Trash->value, ProductInterface::class);
    Route::post('/{code}/restore', ProductRestoreController::class)->can(Ability::Restore->value, ProductInterface::class);
    Route::get('/{code}', ProductFindController::class)->can(Ability::View->value, ProductInterface::class);
    Route::put('/{code}', ProductUpdateController::class)->can(Ability::Update->value, ProductInterface::class);
    Route::patch('/{code}', ProductUpdateController::class)->can(Ability::Update->value, ProductInterface::class);
    Route::delete('/{code}', ProductDeleteController::class)->can(Ability::Delete->value, ProductInterface::class);
});

Route::prefix('attributes')->group(function () {
    Route::get('/', AttributeIndexController::class)->can(Ability::ViewAny->value, AttributeInterface::class);
    Route::post('/', AttributeCreateController::class)->can(Ability::Create->value, AttributeInterface::class);
    Route::get('/trashed', AttributeTrashedController::class)->can(Ability::ViewTrashed->value, AttributeInterface::class);
    Route::post('/{code}/trash', AttributeTrashController::class)->can(Ability::Trash->value, AttributeInterface::class);
    Route::post('/{code}/restore', AttributeRestoreController::class)->can(Ability::Restore->value, AttributeInterface::class);
    Route::get('/{code}', AttributeFindController::class)->can(Ability::View->value, AttributeInterface::class);
    Route::put('/{code}', AttributeUpdateController::class)->can(Ability::Update->value, AttributeInterface::class);
    Route::patch('/{code}', AttributeUpdateController::class)->can(Ability::Update->value, AttributeInterface::class);
    Route::delete('/{code}', AttributeDeleteController::class)->can(Ability::Delete->value, AttributeInterface::class);

    Route::prefix('{attribute}/values')->group(function () {
        Route::get('/', AttributeValueIndexController::class)->can(Ability::ViewAny->value, AttributeValueInterface::class);
        Route::post('/', AttributeValueCreateController::class)->can(Ability::Create->value, AttributeValueInterface::class);
        Route::get('/trashed', AttributeValueTrashedController::class)->can(Ability::ViewTrashed->value, AttributeValueInterface::class);
        Route::post('/{value}/trash', AttributeValueTrashController::class)->can(Ability::Trash->value, AttributeValueInterface::class);
        Route::post('/{value}/restore', AttributeValueRestoreController::class)->can(Ability::Restore->value, AttributeValueInterface::class);
        Route::get('/{value}', AttributeValueFindController::class)->can(Ability::View->value, AttributeValueInterface::class);
        Route::put('/{value}', AttributeValueUpdateController::class)->can(Ability::Update->value, AttributeValueInterface::class);
        Route::patch('/{value}', AttributeValueUpdateController::class)->can(Ability::Update->value, AttributeValueInterface::class);
        Route::delete('/{value}', AttributeValueDeleteController::class)->can(Ability::Delete->value, AttributeValueInterface::class);
    });
});

Route::prefix('blueprints')->group(function () {
    Route::get('/', BlueprintIndexController::class)->can(Ability::ViewAny->value, BlueprintInterface::class);
    Route::post('/', BlueprintCreateController::class)->can(Ability::Create->value, BlueprintInterface::class);
    Route::get('/trashed', BlueprintTrashedController::class)->can(Ability::ViewTrashed->value, BlueprintInterface::class);
    Route::post('/{code}/trash', BlueprintTrashController::class)->can(Ability::Trash->value, BlueprintInterface::class);
    Route::post('/{code}/restore', BlueprintRestoreController::class)->can(Ability::Restore->value, BlueprintInterface::class);
    Route::get('/{code}', BlueprintFindController::class)->can(Ability::View->value, BlueprintInterface::class);
    Route::put('/{code}', BlueprintUpdateController::class)->can(Ability::Update->value, BlueprintInterface::class);
    Route::patch('/{code}', BlueprintUpdateController::class)->can(Ability::Update->value, BlueprintInterface::class);
    Route::delete('/{code}', BlueprintDeleteController::class)->can(Ability::Delete->value, BlueprintInterface::class);
});

Route::prefix('collections')->group(function () {
    Route::get('/', ProductCollectionIndexController::class)->can(Ability::ViewAny->value, ProductCollectionInterface::class);
    Route::post('/', ProductCollectionCreateController::class)->can(Ability::Create->value, ProductCollectionInterface::class);
    Route::get('/trashed', ProductCollectionTrashedController::class)->can(Ability::ViewTrashed->value, ProductCollectionInterface::class);
    Route::post('/{code}/trash', ProductCollectionTrashController::class)->can(Ability::Trash->value, ProductCollectionInterface::class);
    Route::post('/{code}/restore', ProductCollectionRestoreController::class)->can(Ability::Restore->value, ProductCollectionInterface::class);
    Route::get('/{code}', ProductCollectionFindController::class)->can(Ability::View->value, ProductCollectionInterface::class);
    Route::put('/{code}', ProductCollectionUpdateController::class)->can(Ability::Update->value, ProductCollectionInterface::class);
    Route::patch('/{code}', ProductCollectionUpdateController::class)->can(Ability::Update->value, ProductCollectionInterface::class);
    Route::delete('/{code}', ProductCollectionDeleteController::class)->can(Ability::Delete->value, ProductCollectionInterface::class);
});
