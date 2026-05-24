<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Product\Requests\ProductCollection\ProductCollectionIndexRequest;
use Gildsmith\Support\Facades\ProductCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class ProductCollectionIndexController extends Controller
{
    public function __invoke(ProductCollectionIndexRequest $request): Collection
    {
        return ProductCollection::all();
    }
}
