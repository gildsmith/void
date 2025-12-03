<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\AttributeValue;

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AttributeValueFindController extends Controller
{
    public function __invoke(string $attribute, string $value): AttributeValueInterface
    {
        $attributeModel = Product::attribute()->find($attribute);

        abort_if(! $attributeModel, Response::HTTP_NOT_FOUND);

        $valueModel = $attributeModel->values()->where('code', $value)->first();

        abort_if(! $valueModel, Response::HTTP_NOT_FOUND);

        return $valueModel;
    }
}
