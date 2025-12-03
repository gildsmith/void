<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AttributeCreateController extends Controller
{
    public function __invoke(Request $request): AttributeInterface
    {
        return Product::attribute()->create($request->all());
    }
}
