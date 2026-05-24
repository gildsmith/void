<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\AttributeValue;

use Gildsmith\Product\Requests\AttributeValue\AttributeValueTrashedRequest;
use Gildsmith\Support\Facades\Attribute;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class AttributeValueTrashedController extends Controller
{
    public function __invoke(AttributeValueTrashedRequest $request, string $attribute): Collection
    {
        $attributeModel = Attribute::find($attribute);

        abort_if(! $attributeModel, Response::HTTP_NOT_FOUND);

        return $attributeModel->values()->onlyTrashed()->get();
    }
}
