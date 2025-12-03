<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BlueprintUpdateController extends Controller
{
    public function __invoke(Request $request, string $code): BlueprintInterface
    {
        return Product::blueprint()->updateOrCreate($code, $request->except('code'));
    }
}
