<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Routing\Controller;

class BlueprintRestoreController extends Controller
{
    public function __invoke(string $code): bool
    {
        return Blueprint::restore($code);
    }
}
