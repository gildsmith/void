<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class ProductRestoreController extends Controller
{
    public function __invoke(string $code): bool
    {
        return Product::restore($code);
    }
}
