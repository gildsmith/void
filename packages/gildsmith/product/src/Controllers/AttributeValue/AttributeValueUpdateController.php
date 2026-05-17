<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\AttributeValue;

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Support\Facades\Attribute;
use Gildsmith\Support\Facades\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AttributeValueUpdateController extends Controller
{
    public function __invoke(Request $request, string $attribute, string $value): ?AttributeValueInterface
    {
        $attributeModel = Attribute::find($attribute);
        $valueModel = AttributeValue::find($value, true);

        abort_if(! $attributeModel, Response::HTTP_NOT_FOUND);
        abort_if(! $valueModel || $valueModel->getAttribute('attribute_id') !== $attributeModel->getKey(), Response::HTTP_NOT_FOUND);

        $data = $request->all();
        $data['attribute_id'] = $attributeModel->getKey();

        return AttributeValue::update($value, $data);
    }
}
