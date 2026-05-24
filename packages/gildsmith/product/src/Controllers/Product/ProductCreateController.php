<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Requests\Product\ProductCreateRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class ProductCreateController extends Controller
{
    public function __invoke(ProductCreateRequest $request): ProductInterface
    {
        return Product::create($request->all());
    }
}
