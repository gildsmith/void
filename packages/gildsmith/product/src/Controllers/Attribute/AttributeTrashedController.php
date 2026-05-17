<?php

declare(strict_types=1);

namespace Gildsmith\Product\Controllers\Attribute;

use Gildsmith\Support\Facades\Attribute;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class AttributeTrashedController extends Controller
{
    public function __invoke(): Collection
    {
        return Attribute::trashed();
    }
}
