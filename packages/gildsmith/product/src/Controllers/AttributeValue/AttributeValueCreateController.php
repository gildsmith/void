<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\AttributeValue;

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AttributeValueCreateController extends Controller
{
    public function __invoke(Request $request, string $attribute): AttributeValueInterface
    {
        $attributeModel = Product::attribute()->find($attribute);

        return $attributeModel->values()->forceCreate($request->all());
    }
}
