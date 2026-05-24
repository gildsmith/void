<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Product\Requests\Attribute\AttributeUpdateRequest;
use Gildsmith\Support\Facades\Attribute;
use Illuminate\Routing\Controller;

class AttributeUpdateController extends Controller
{
    public function __invoke(AttributeUpdateRequest $request, string $code): ?AttributeInterface
    {
        return Attribute::update($code, $request->all());
    }
}
