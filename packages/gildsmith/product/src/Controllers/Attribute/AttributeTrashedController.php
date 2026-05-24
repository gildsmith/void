<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Product\Requests\Attribute\AttributeTrashedRequest;
use Gildsmith\Support\Facades\Attribute;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class AttributeTrashedController extends Controller
{
    public function __invoke(AttributeTrashedRequest $request): Collection
    {
        return Attribute::trashed();
    }
}
