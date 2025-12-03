<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\AttributeValue;

use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class AttributeValueTrashedController extends Controller
{
    public function __invoke(string $attribute): Collection
    {
        $attributeModel = Product::attribute()->find($attribute);

        return $attributeModel->values()->onlyTrashed()->get();
    }
}
