<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Product\Requests\Attribute\AttributeTrashRequest;
use Gildsmith\Support\Facades\Attribute;
use Illuminate\Routing\Controller;

class AttributeTrashController extends Controller
{
    public function __invoke(AttributeTrashRequest $request, string $code): bool
    {
        return Attribute::delete($code);
    }
}
