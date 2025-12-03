<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class ProductCollectionIndexController extends Controller
{
    public function __invoke(): Collection
    {
        return Product::collection()->all();
    }
}
