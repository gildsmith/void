<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\Facades\ProductFacadeInterface;
use Gildsmith\Product\Controllers\Product\ProductDeleteController;
use Gildsmith\Product\Requests\Product\ProductDeleteRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery\MockInterface;

covers(ProductDeleteController::class);

describe('__invoke', function () {
    it('returns a clear not-found response when the product does not exist', function () {
        /** @var ProductFacadeInterface&MockInterface $facade */
        $facade = Mockery::mock(ProductFacadeInterface::class);
        $facade->shouldReceive('delete')->once()->with('missing', true)->andReturnFalse();
        Product::swap($facade);

        $request = ProductDeleteRequest::create('/products/missing', 'DELETE');
        $response = new ProductDeleteController()($request, 'missing');

        expect($response)->toBeInstanceOf(JsonResponse::class);
        expect($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND);
        expect($response->getData(true))->toBe(['message' => 'Product not found.']);
    });
});
