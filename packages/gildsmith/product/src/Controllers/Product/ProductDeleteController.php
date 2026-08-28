<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductDeleteRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductDeleteController extends Controller
{
    public function __invoke(ProductDeleteRequest $request, string $code): bool|JsonResponse
    {
        $deleted = Product::delete($code, true);

        if (! $deleted) {
            return response()->json(
                ['message' => 'Product not found.'],
                Response::HTTP_NOT_FOUND,
            );
        }

        return true;
    }
}
