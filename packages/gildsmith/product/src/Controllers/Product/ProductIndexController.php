<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductIndexRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class ProductIndexController extends Controller
{
    public function __invoke(ProductIndexRequest $request): Collection
    {
        return Product::all();
    }
}
