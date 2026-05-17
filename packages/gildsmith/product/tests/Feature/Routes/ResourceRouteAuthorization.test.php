<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;

itExposesTrashableResourceRoutes(
    uri: 'products',
    contract: ProductInterface::class,
);

itExposesTrashableResourceRoutes(
    uri: 'attributes',
    contract: AttributeInterface::class,
);

itExposesTrashableResourceRoutes(
    uri: 'attributes/{attribute}/values',
    contract: AttributeValueInterface::class,
    parameter: 'value',
);

itExposesTrashableResourceRoutes(
    uri: 'blueprints',
    contract: BlueprintInterface::class,
);

itExposesTrashableResourceRoutes(
    uri: 'collections',
    contract: ProductCollectionInterface::class,
);
