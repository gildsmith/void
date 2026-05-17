<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\BlueprintInterface;

itExposesTrashableResourceRoutes(
    uri: 'blueprints',
    contract: BlueprintInterface::class,
);
