<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Product\Requests\Blueprint\BlueprintTrashedRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class BlueprintTrashedController extends Controller
{
    public function __invoke(BlueprintTrashedRequest $request): Collection
    {
        return Blueprint::trashed();
    }
}
