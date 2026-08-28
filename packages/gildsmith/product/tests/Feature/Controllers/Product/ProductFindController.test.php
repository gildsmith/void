<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\Facades\ProductFacadeInterface;
use Gildsmith\Product\Controllers\Product\ProductFindController;
use Gildsmith\Product\Requests\Product\ProductFindRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery\MockInterface;

covers(ProductFindController::class);

describe('__invoke', function () {
    it('returns a clear not-found response when the product does not exist', function () {
        /** @var ProductFacadeInterface&MockInterface $facade */
        $facade = Mockery::mock(ProductFacadeInterface::class);
        $facade->shouldReceive('find')->once()->with('missing', false)->andReturnNull();
        Product::swap($facade);

        $request = ProductFindRequest::create('/products/missing', 'GET');
        $response = (new ProductFindController())($request, 'missing');

        expect($response)->toBeInstanceOf(JsonResponse::class);
        expect($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND);
        expect($response->getData(true))->toBe(['message' => 'Product not found.']);
    });
});
