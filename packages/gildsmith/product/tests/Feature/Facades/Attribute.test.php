<?php

declare(strict_types=1);

use Gildsmith\Product\Facades\AttributeFacade as AttributeFacadeConcrete;
use Gildsmith\Product\Models\Attribute;
use Gildsmith\Support\Facades\Attribute as AttributeFacade;

covers(AttributeFacadeConcrete::class);

itFulfillsTrashableFacadeContract(
    facade: AttributeFacade::class,
    model: Attribute::class,
);
