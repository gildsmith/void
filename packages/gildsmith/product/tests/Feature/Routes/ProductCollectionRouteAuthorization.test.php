<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\ProductCollectionInterface;

itExposesTrashableResourceRoutes(
    uri: 'collections',
    contract: ProductCollectionInterface::class,
);
