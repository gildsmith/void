<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Blueprint;

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Product\Requests\Blueprint\BlueprintUpdateRequest;
use Gildsmith\Support\Facades\Blueprint;
use Illuminate\Routing\Controller;

class BlueprintUpdateController extends Controller
{
    public function __invoke(BlueprintUpdateRequest $request, string $code): ?BlueprintInterface
    {
        return Blueprint::update($code, $request->all());
    }
}
