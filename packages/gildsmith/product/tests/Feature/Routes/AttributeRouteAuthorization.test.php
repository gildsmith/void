<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\AttributeInterface;

itExposesTrashableResourceRoutes(
    uri: 'attributes',
    contract: AttributeInterface::class,
);
