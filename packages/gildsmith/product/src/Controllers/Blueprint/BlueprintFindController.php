<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class BlueprintFindController extends Controller
{
    public function __invoke(string $code): ?BlueprintInterface
    {
        return Product::blueprint()->find($code);
    }
}
