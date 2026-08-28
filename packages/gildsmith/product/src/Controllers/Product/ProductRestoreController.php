<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductRestoreRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductRestoreController extends Controller
{
    public function __invoke(ProductRestoreRequest $request, string $code): bool|JsonResponse
    {
        $restored = Product::restore($code);

        if (! $restored) {
            return response()->json(
                ['message' => 'Product not found.'],
                Response::HTTP_NOT_FOUND,
            );
        }

        return true;
    }
}
