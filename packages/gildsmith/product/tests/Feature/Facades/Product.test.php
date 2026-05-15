<?php

declare(strict_types=1);

use Gildsmith\Product\Facades\ProductFacade as ProductFacadeConcrete;
use Gildsmith\Product\Models\Product;
use Gildsmith\Support\Facades\Product as ProductFacade;

covers(ProductFacadeConcrete::class);

itFulfillsTrashableFacadeContract(
    facade: ProductFacade::class,
    model: Product::class,
);
