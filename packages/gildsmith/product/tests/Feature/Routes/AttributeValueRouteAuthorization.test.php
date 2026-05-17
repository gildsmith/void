<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\AttributeValueInterface;

itExposesTrashableResourceRoutes(
    uri: 'attributes/{attribute}/values',
    contract: AttributeValueInterface::class,
    parameter: 'value',
);
