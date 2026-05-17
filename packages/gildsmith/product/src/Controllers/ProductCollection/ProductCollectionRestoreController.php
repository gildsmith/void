<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Support\Facades\ProductCollection;
use Illuminate\Routing\Controller;

class ProductCollectionRestoreController extends Controller
{
    public function __invoke(string $code): bool
    {
        return ProductCollection::restore($code);
    }
}
