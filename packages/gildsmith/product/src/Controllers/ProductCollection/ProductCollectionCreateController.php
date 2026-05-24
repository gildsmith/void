<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Product\Requests\ProductCollection\ProductCollectionCreateRequest;
use Gildsmith\Support\Facades\ProductCollection;
use Illuminate\Routing\Controller;

class ProductCollectionCreateController extends Controller
{
    public function __invoke(ProductCollectionCreateRequest $request): ProductCollectionInterface
    {
        return ProductCollection::create($request->all());
    }
}
