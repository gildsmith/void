<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Product\Models\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

covers(Attribute::class);

it('has a values relationship', function () {
    $model = Attribute::factory()->hasValues(3)->create();

    $relationship = $model->values();
    $relatedModel = $relationship->getRelated();
    $collectionCount = $model->values->count();

    expect($collectionCount)->toBe(3);
    expect($relationship)->toBeInstanceOf(HasMany::class);
    expect($relatedModel)->toBeInstanceOf(AttributeValueInterface::class);
});

it('has a blueprints relationship', function () {
    $model = Attribute::factory()->hasBlueprints(3)->create();

    $relationship = $model->blueprints();
    $relatedModel = $relationship->getRelated();
    $collectionCount = $model->blueprints->count();

    expect($collectionCount)->toBe(3);
    expect($relationship)->toBeInstanceOf(BelongsToMany::class);
    expect($relatedModel)->toBeInstanceOf(BlueprintInterface::class);
});
