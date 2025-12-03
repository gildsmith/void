<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class AttributeRestoreController extends Controller
{
    public function __invoke(string $code): bool
    {
        return Product::attribute()->restore($code);
    }
}
