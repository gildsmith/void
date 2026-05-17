<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Support\Facades\ProductCollection;
use Illuminate\Routing\Controller;

class ProductCollectionFindController extends Controller
{
    public function __invoke(string $code): ?ProductCollectionInterface
    {
        return ProductCollection::find($code);
    }
}
