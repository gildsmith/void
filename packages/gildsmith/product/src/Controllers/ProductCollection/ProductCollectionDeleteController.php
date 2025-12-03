<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class ProductCollectionDeleteController extends Controller
{
    public function __invoke(string $code): bool
    {
        return Product::collection()->delete($code, true);
    }
}
