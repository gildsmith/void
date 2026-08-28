<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductUpdateRequest;
use Gildsmith\Product\Resources\ProductResource;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductUpdateController extends Controller
{
    public function __invoke(ProductUpdateRequest $request, string $code): JsonResponse|ProductResource
    {
        $product = Product::update($code, $request->all());

        if ($product === null) {
            return response()->json(
                ['message' => 'Product not found.'],
                Response::HTTP_NOT_FOUND,
            );
        }

        return new ProductResource($product);
    }
}
