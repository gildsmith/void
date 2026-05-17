<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\ProductCollection;

use Gildsmith\Support\Facades\ProductCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class ProductCollectionTrashedController extends Controller
{
    public function __invoke(): Collection
    {
        return ProductCollection::trashed();
    }
}
