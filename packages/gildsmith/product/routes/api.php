<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Contract\Shared\Routing\ResourceAbility;
use Gildsmith\Product\Controllers\Product\ProductCreateController;
use Gildsmith\Product\Controllers\Product\ProductDeleteController;
use Gildsmith\Product\Controllers\Product\ProductFindController;
use Gildsmith\Product\Controllers\Product\ProductIndexController;
use Gildsmith\Product\Controllers\Product\ProductRestoreController;
use Gildsmith\Product\Controllers\Product\ProductTrashController;
use Gildsmith\Product\Controllers\Product\ProductTrashedController;
use Gildsmith\Product\Controllers\Product\ProductUpdateController;
use Illuminate\Support\Facades\Route;

Route::get('/products', ProductIndexController::class)->can(ResourceAbility::ViewAny->value, ProductInterface::class);
Route::post('/products', ProductCreateController::class)->can(ResourceAbility::Create->value, ProductInterface::class);
Route::get('/products/trashed', ProductTrashedController::class)->can(ResourceAbility::ViewTrashed->value, ProductInterface::class);
Route::post('/products/{code}/trash', ProductTrashController::class)->can(ResourceAbility::Trash->value, ProductInterface::class);
Route::post('/products/{code}/restore', ProductRestoreController::class)->can(ResourceAbility::Restore->value, ProductInterface::class);
Route::get('/products/{code}', ProductFindController::class)->can(ResourceAbility::View->value, ProductInterface::class);
Route::put('/products/{code}', ProductUpdateController::class)->can(ResourceAbility::Update->value, ProductInterface::class);
Route::patch('/products/{code}', ProductUpdateController::class)->can(ResourceAbility::Update->value, ProductInterface::class);
Route::delete('/products/{code}', ProductDeleteController::class)->can(ResourceAbility::Delete->value, ProductInterface::class);
