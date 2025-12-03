<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class BlueprintTrashController extends Controller
{
    public function __invoke(string $code): bool
    {
        return Product::blueprint()->delete($code);
    }
}
