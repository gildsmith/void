<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\AttributeValue;

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Product\Requests\AttributeValue\AttributeValueCreateRequest;
use Gildsmith\Support\Facades\Attribute;
use Gildsmith\Support\Facades\AttributeValue;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AttributeValueCreateController extends Controller
{
    public function __invoke(AttributeValueCreateRequest $request, string $attribute): AttributeValueInterface
    {
        $attributeModel = Attribute::find($attribute);

        abort_if(! $attributeModel, Response::HTTP_NOT_FOUND);

        $data = $request->all();
        $data['attribute_id'] = $attributeModel->getKey();

        return AttributeValue::create($data);
    }
}
