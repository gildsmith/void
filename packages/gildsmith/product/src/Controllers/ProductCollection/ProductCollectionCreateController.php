<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductCollectionCreateController extends Controller
{
    public function __invoke(Request $request): ProductCollectionInterface
    {
        return Product::collection()->create($request->all());
    }
}
