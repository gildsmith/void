<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductFindRequest;
use Gildsmith\Product\Resources\ProductResource;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductFindController extends Controller
{
    public function __invoke(ProductFindRequest $request, string $code): JsonResponse|ProductResource
    {
        $withTrashed = $request->boolean('withTrashed');
        $product = Product::find($code, $withTrashed);

        if ($product === null) {
            return response()->json(
                ['message' => 'Product not found.'],
                Response::HTTP_NOT_FOUND,
            );
        }

        return new ProductResource($product);
    }
}
