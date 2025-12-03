<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductFindController extends Controller
{
    public function __invoke(Request $request, string $code): ProductInterface
    {
        $withTrashed = $request->boolean('withTrashed');

        $product = Product::find($code, $withTrashed);

        abort_if(! $product, Response::HTTP_NOT_FOUND);

        return $product;
    }
}
