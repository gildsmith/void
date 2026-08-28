<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductCreateRequest;
use Gildsmith\Product\Resources\ProductResource;
use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class ProductCreateController extends Controller
{
    public function __invoke(ProductCreateRequest $request): ProductResource
    {
        $product = Product::create($request->all());

        return new ProductResource($product);
    }
}
