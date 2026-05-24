<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Product\Requests\ProductCollection\ProductCollectionDeleteRequest;
use Gildsmith\Support\Facades\ProductCollection;
use Illuminate\Routing\Controller;

class ProductCollectionDeleteController extends Controller
{
    public function __invoke(ProductCollectionDeleteRequest $request, string $code): bool
    {
        return ProductCollection::delete($code, true);
    }
}
