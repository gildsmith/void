<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Product\Requests\Blueprint\BlueprintFindRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Routing\Controller;

class BlueprintFindController extends Controller
{
    public function __invoke(BlueprintFindRequest $request, string $code): ?BlueprintInterface
    {
        return Blueprint::find($code);
    }
}
