<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Product\Requests\ProductCollection\ProductCollectionTrashRequest;
use Gildsmith\Support\Facades\ProductCollection;
use Illuminate\Routing\Controller;

class ProductCollectionTrashController extends Controller
{
    public function __invoke(ProductCollectionTrashRequest $request, string $code): bool
    {
        return ProductCollection::delete($code);
    }
}
