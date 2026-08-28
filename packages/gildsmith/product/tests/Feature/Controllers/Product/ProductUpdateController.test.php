<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\Facades\ProductFacadeInterface;
use Gildsmith\Product\Controllers\Product\ProductUpdateController;
use Gildsmith\Product\Requests\Product\ProductUpdateRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery\MockInterface;

covers(ProductUpdateController::class);

describe('__invoke', function () {
    it('returns a clear not-found response when the product does not exist', function () {
        /** @var ProductFacadeInterface&MockInterface $facade */
        $facade = Mockery::mock(ProductFacadeInterface::class);
        $facade->shouldReceive('update')->once()->with('missing', ['name' => 'Missing'])->andReturnNull();
        Product::swap($facade);

        $request = ProductUpdateRequest::create('/products/missing', 'PATCH', ['name' => 'Missing']);
        $response = (new ProductUpdateController())($request, 'missing');

        expect($response)->toBeInstanceOf(JsonResponse::class);
        expect($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND);
        expect($response->getData(true))->toBe(['message' => 'Product not found.']);
    });
});
