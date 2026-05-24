<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Product\Requests\Blueprint\BlueprintTrashRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Routing\Controller;

class BlueprintTrashController extends Controller
{
    public function __invoke(BlueprintTrashRequest $request, string $code): bool
    {
        return Blueprint::delete($code);
    }
}
