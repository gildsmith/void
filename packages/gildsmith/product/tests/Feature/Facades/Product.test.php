<?php

declare(strict_types=1);

use Gildsmith\Product\Facades\ProductFacade as ProductFacadeConcrete;
use Gildsmith\Support\Facades\Product as ProductFacade;
use Gildsmith\Product\Models\Product;

covers(ProductFacadeConcrete::class);

itFulfillsCrudFacadeContract(
    facade: ProductFacade::class,
    model: Product::class
);
