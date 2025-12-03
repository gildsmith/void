<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class ProductIndexController extends Controller
{
    public function __invoke(Request $request): Collection
    {
        return Product::all();
    }
}
