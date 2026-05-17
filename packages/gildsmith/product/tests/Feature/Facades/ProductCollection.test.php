<?php

declare(strict_types=1);

use Gildsmith\Product\Facades\ProductCollectionFacade as ProductCollectionFacadeConcrete;
use Gildsmith\Product\Models\ProductCollection;
use Gildsmith\Support\Facades\ProductCollection as ProductCollectionFacade;

covers(ProductCollectionFacadeConcrete::class);

itFulfillsTrashableFacadeContract(
    facade: ProductCollectionFacade::class,
    model: ProductCollection::class,
);
