<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductIndexRequest;
use Gildsmith\Product\Resources\ProductResource;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class ProductIndexController extends Controller
{
    public function __invoke(ProductIndexRequest $request): AnonymousResourceCollection
    {
        // TODO: Add pagination.
        $products = Product::all();

        return ProductResource::collection($products);
    }
}
