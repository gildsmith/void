<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Models\ProductCollection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

covers(ProductCollection::class);

it('has products relationship', function () {
    $model = ProductCollection::factory()->hasProducts(3)->create();

    $relationship = $model->products();
    $relatedModel = $relationship->getRelated();
    $collectionCount = $model->products->count();

    expect($collectionCount)->toBe(3);
    expect($relationship)->toBeInstanceOf(BelongsToMany::class);
    expect($relatedModel)->toBeInstanceOf(ProductInterface::class);
});
