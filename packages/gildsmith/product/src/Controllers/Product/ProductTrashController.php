<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductTrashRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductTrashController extends Controller
{
    public function __invoke(ProductTrashRequest $request, string $code): bool|JsonResponse
    {
        $trashed = Product::delete($code);

        if (! $trashed) {
            return response()->json(
                ['message' => 'Product not found.'],
                Response::HTTP_NOT_FOUND,
            );
        }

        return true;
    }
}
