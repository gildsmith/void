<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Product\Requests\Blueprint\BlueprintIndexRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class BlueprintIndexController extends Controller
{
    public function __invoke(BlueprintIndexRequest $request): Collection
    {
        return Blueprint::all();
    }
}
