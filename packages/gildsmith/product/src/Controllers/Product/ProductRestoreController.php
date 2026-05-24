<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Product\Requests\Product\ProductRestoreRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class ProductRestoreController extends Controller
{
    public function __invoke(ProductRestoreRequest $request, string $code): bool
    {
        return Product::restore($code);
    }
}
