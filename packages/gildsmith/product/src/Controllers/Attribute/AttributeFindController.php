<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Support\Facades\Product;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AttributeFindController extends Controller
{
    public function __invoke(string $code): AttributeInterface
    {
        $attribute = Product::attribute()->find($code);

        abort_if(! $attribute, Response::HTTP_NOT_FOUND);

        return $attribute;
    }
}
