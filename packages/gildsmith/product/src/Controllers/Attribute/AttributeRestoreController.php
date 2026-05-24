<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Product\Requests\Attribute\AttributeRestoreRequest;
use Gildsmith\Support\Facades\Attribute;
use Illuminate\Routing\Controller;

class AttributeRestoreController extends Controller
{
    public function __invoke(AttributeRestoreRequest $request, string $code): bool
    {
        return Attribute::restore($code);
    }
}
