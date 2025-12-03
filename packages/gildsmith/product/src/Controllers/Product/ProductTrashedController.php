<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Product;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class ProductTrashedController extends Controller
{
    public function __invoke(): Collection
    {
        return Product::trashed();
    }
}
