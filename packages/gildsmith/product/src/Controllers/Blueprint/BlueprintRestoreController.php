<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class BlueprintRestoreController extends Controller
{
    public function __invoke(string $code): bool
    {
        return Product::blueprint()->restore($code);
    }
}
