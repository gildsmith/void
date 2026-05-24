<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Product\Requests\Attribute\AttributeFindRequest;
use Gildsmith\Support\Facades\Attribute;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AttributeFindController extends Controller
{
    public function __invoke(AttributeFindRequest $request, string $code): AttributeInterface
    {
        $attribute = Attribute::find($code);

        abort_if(! $attribute, Response::HTTP_NOT_FOUND);

        return $attribute;
    }
}
