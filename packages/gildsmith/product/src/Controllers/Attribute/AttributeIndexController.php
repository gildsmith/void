<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Product\Requests\Attribute\AttributeIndexRequest;
use Gildsmith\Support\Facades\Attribute;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class AttributeIndexController extends Controller
{
    public function __invoke(AttributeIndexRequest $request): Collection
    {
        return Attribute::all();
    }
}
