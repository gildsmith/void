<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class AttributeTrashController extends Controller
{
    public function __invoke(string $code): bool
    {
        return Product::attribute()->delete($code);
    }
}
