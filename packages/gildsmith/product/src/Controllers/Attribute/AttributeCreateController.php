<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Product\Requests\Attribute\AttributeCreateRequest;
use Gildsmith\Support\Facades\Attribute;
use Illuminate\Routing\Controller;

class AttributeCreateController extends Controller
{
    public function __invoke(AttributeCreateRequest $request): AttributeInterface
    {
        return Attribute::create($request->all());
    }
}
