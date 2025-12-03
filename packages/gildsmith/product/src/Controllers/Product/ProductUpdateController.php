<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductUpdateController extends Controller
{
    public function __invoke(Request $request, string $code): ProductInterface
    {
        return Product::update($code, $request->all());
    }
}
