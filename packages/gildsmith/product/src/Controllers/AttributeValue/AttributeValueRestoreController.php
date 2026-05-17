<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\AttributeValue;

use Gildsmith\Support\Facades\Attribute;
use Gildsmith\Support\Facades\AttributeValue;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AttributeValueRestoreController extends Controller
{
    public function __invoke(string $attribute, string $value): bool
    {
        $attributeModel = Attribute::find($attribute);
        $valueModel = AttributeValue::find($value, true);

        abort_if(! $attributeModel, Response::HTTP_NOT_FOUND);
        abort_if(! $valueModel || $valueModel->getAttribute('attribute_id') !== $attributeModel->getKey(), Response::HTTP_NOT_FOUND);

        return AttributeValue::restore($value);
    }
}
