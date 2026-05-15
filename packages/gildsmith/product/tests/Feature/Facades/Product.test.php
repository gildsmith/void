<?php

declare(strict_types=1);

use Gildsmith\Product\Facades\ProductFacade as ProductFacadeConcrete;
use Gildsmith\Product\Models\Blueprint;
use Gildsmith\Product\Models\Product;
use Gildsmith\Support\Facades\Product as ProductFacade;

covers(ProductFacadeConcrete::class);

itFulfillsTrashableFacadeContract(
    facade: ProductFacade::class,
    model: Product::class,
    createData: fn (): array => [
        'code' => 'product_'.str()->random(12),
        'blueprint_id' => Blueprint::factory()->create()->getKey(),
        'name' => [
            'en' => 'Plain shirt',
            'pl' => 'Prosta koszula',
        ],
    ],
    updateData: fn (): array => [
        'blueprint_id' => Blueprint::factory()->create()->getKey(),
        'name' => [
            'en' => 'Tailored shirt',
            'pl' => 'Koszula szyta',
        ],
    ],
);
