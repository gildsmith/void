<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Models\AttributeValue;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

covers(AttributeValue::class);

it('has an attribute relationship', function () {
    $model = AttributeValue::factory()->create();

    $relationship = $model->attribute();
    $relatedModel = $model->attribute;

    expect($relationship)->toBeInstanceOf(BelongsTo::class);
    expect($relatedModel)->toBeInstanceOf(AttributeInterface::class);
});

it('has a products relationship', function () {
    $model = AttributeValue::factory()->hasProducts(3)->create();

    $relationship = $model->products();
    $relatedModel = $relationship->getRelated();
    $collectionCount = $model->products->count();

    expect($collectionCount)->toBe(3);
    expect($relationship)->toBeInstanceOf(BelongsToMany::class);
    expect($relatedModel)->toBeInstanceOf(ProductInterface::class);
});
