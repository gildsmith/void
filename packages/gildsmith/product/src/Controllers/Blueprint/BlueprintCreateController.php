<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Product\Requests\Blueprint\BlueprintCreateRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Routing\Controller;

class BlueprintCreateController extends Controller
{
    public function __invoke(BlueprintCreateRequest $request): BlueprintInterface
    {
        return Blueprint::create($request->all());
    }
}
