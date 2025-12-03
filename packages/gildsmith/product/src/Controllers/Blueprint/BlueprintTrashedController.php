<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class BlueprintTrashedController extends Controller
{
    public function __invoke(): Collection
    {
        return Product::blueprint()->trashed();
    }
}
