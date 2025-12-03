<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AttributeUpdateController extends Controller
{
    public function __invoke(Request $request, string $code): AttributeInterface
    {
        return Product::attribute()->updateOrCreate($code, $request->except('code'));
    }
}
