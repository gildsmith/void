<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Product\Requests\ProductCollection\ProductCollectionUpdateRequest;
use Gildsmith\Support\Facades\ProductCollection;
use Illuminate\Routing\Controller;

class ProductCollectionUpdateController extends Controller
{
    public function __invoke(ProductCollectionUpdateRequest $request, string $code): ?ProductCollectionInterface
    {
        return ProductCollection::update($code, $request->all());
    }
}
