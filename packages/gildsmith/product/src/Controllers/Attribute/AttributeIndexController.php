<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class AttributeIndexController extends Controller
{
    public function __invoke(): Collection
    {
        return Product::attribute()->all();
    }
}
