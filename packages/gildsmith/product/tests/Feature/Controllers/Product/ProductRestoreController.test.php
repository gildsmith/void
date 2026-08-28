<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\Facades\ProductFacadeInterface;
use Gildsmith\Product\Controllers\Product\ProductRestoreController;
use Gildsmith\Product\Requests\Product\ProductRestoreRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery\MockInterface;

covers(ProductRestoreController::class);

describe('__invoke', function () {
    it('returns a clear not-found response when the product does not exist', function () {
        /** @var ProductFacadeInterface&MockInterface $facade */
        $facade = Mockery::mock(ProductFacadeInterface::class);
        $facade->shouldReceive('restore')->once()->with('missing')->andReturnFalse();
        Product::swap($facade);

        $request = ProductRestoreRequest::create('/products/missing/restore', 'POST');
        $response = (new ProductRestoreController())($request, 'missing');

        expect($response)->toBeInstanceOf(JsonResponse::class);
        expect($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND);
        expect($response->getData(true))->toBe(['message' => 'Product not found.']);
    });
});
