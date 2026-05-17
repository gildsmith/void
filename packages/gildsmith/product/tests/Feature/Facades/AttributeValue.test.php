<?php

declare(strict_types=1);

use Gildsmith\Product\Facades\AttributeValueFacade as AttributeValueFacadeConcrete;
use Gildsmith\Product\Models\AttributeValue;
use Gildsmith\Support\Facades\AttributeValue as AttributeValueFacade;

covers(AttributeValueFacadeConcrete::class);

itFulfillsTrashableFacadeContract(
    facade: AttributeValueFacade::class,
    model: AttributeValue::class,
);
