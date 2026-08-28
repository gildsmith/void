<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductTrashedRequest;
use Gildsmith\Product\Resources\ProductResource;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class ProductTrashedController extends Controller
{
    public function __invoke(ProductTrashedRequest $request): AnonymousResourceCollection
    {
        $products = Product::trashed();

        return ProductResource::collection($products);
    }
}
