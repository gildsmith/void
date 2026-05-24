<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Product\Requests\Attribute\AttributeDeleteRequest;
use Gildsmith\Support\Facades\Attribute;
use Illuminate\Routing\Controller;

class AttributeDeleteController extends Controller
{
    public function __invoke(AttributeDeleteRequest $request, string $code): bool
    {
        return Attribute::delete($code, true);
    }
}
