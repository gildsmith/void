<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\ProductInterface;

itExposesTrashableResourceRoutes(
    uri: 'products',
    contract: ProductInterface::class,
);
