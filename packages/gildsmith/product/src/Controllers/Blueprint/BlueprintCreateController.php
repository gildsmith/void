<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BlueprintCreateController extends Controller
{
    public function __invoke(Request $request): BlueprintInterface
    {
        return Blueprint::create($request->all());
    }
}
