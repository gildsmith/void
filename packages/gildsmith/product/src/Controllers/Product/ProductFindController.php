<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Requests\Product\ProductFindRequest;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductFindController extends Controller
{
    public function __invoke(ProductFindRequest $request, string $code): ProductInterface
    {
        $withTrashed = $request->boolean('withTrashed');

        $product = Product::find($code, $withTrashed);

        abort_if(! $product, Response::HTTP_NOT_FOUND);

        return $product;
    }
}
