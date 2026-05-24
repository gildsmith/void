<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Requests\Product\ProductUpdateRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class ProductUpdateController extends Controller
{
    public function __invoke(ProductUpdateRequest $request, string $code): ?ProductInterface
    {
        return Product::update($code, $request->all());
    }
}
