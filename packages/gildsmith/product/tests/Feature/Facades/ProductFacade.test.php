<?php

declare(strict_types=1);

use Gildsmith\Product\Facades\ProductFacade;
use Gildsmith\Product\Models\Product;
use Gildsmith\Support\Facades\Product as ProductSupportFacade;

covers(ProductFacade::class);

itFulfillsTrashableFacadeContract(
    facade: ProductSupportFacade::class,
    model: Product::class,
);
