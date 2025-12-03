<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\AttributeValue;

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Routing\Controller;

class AttributeValueDeleteController extends Controller
{
    public function __invoke(string $attribute, string $value): bool
    {
        $attributeModel = Product::attribute()->find($attribute);

        /** @var AttributeValueInterface $valueModel */
        $valueModel = $attributeModel->values()->withTrashed()->where('code', $value)->firstOrFail();

        return (bool) $valueModel->forceDelete();
    }
}
