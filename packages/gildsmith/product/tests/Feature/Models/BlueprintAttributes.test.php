<?php

declare(strict_types=1);

use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\AttributeValue;
use Gildsmith\Product\Models\Blueprint;
use Gildsmith\Product\Models\Pivots\AttributeBlueprint;
use Gildsmith\Product\Models\Product;
use Illuminate\Database\QueryException;

covers(AttributeBlueprint::class);

it('marks existing blueprint products incomplete when a required attribute is attached', function () {
    $blueprint = Blueprint::factory()->create();
    $product = Product::factory()->for($blueprint)->create();
    $attribute = Attribute::factory()->create();

    expect($product->refresh()->is_complete)->toBeTrue();

    $blueprint->attributes()->attach($attribute->id, ['required' => true]);

    expect($product->refresh()->is_complete)->toBeFalse();
});

it('does not mark existing blueprint products incomplete when an optional attribute is attached', function () {
    $blueprint = Blueprint::factory()->create();
    $product = Product::factory()->for($blueprint)->create();
    $attribute = Attribute::factory()->create();

    $blueprint->attributes()->attach($attribute->id);

    expect($product->refresh()->is_complete)->toBeTrue();
});

it('does not allow the same attribute to be attached to a blueprint twice', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();

    $blueprint->attributes()->attach($attribute->id);

    expect(fn () => $blueprint->attributes()->attach($attribute->id))
        ->toThrow(QueryException::class);
});

it('marks existing blueprint products incomplete when an existing attribute becomes required', function () {
    $blueprint = Blueprint::factory()->create();
    $product = Product::factory()->for($blueprint)->create();
    $attribute = Attribute::factory()->create();

    $blueprint->attributes()->attach($attribute->id, ['required' => false]);
    expect($product->refresh()->is_complete)->toBeTrue();

    $blueprint->attributes()->updateExistingPivot($attribute->id, ['required' => true]);

    expect($product->refresh()->is_complete)->toBeFalse();
});

it('recalculates product completeness from required blueprint attributes', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();
    $value = AttributeValue::factory()->for($attribute)->create();
    $product = Product::factory()->for($blueprint)->create();

    $blueprint->attributes()->attach($attribute->id, ['required' => true]);

    expect($product->refresh()->is_complete)->toBeFalse();

    $product->attributeValues()->attach($value->id);

    expect($product->recalculateCompleteness())->toBeTrue()
        ->and($product->refresh()->is_complete)->toBeTrue();
});

it('can mark product completeness directly when the state is already known', function () {
    $product = Product::factory()->create();

    expect($product->refresh()->is_complete)->toBeTrue();

    expect($product->markIncomplete())->toBeTrue()
        ->and($product->refresh()->is_complete)->toBeFalse();

    expect($product->markComplete())->toBeTrue()
        ->and($product->refresh()->is_complete)->toBeTrue();
});

it('marks new products incomplete when their blueprint already requires missing attributes', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();

    $blueprint->attributes()->attach($attribute->id, ['required' => true]);

    $product = Product::factory()->for($blueprint)->create();

    expect($product->refresh()->is_complete)->toBeFalse();
});

it('cascades detached blueprint attributes from related products', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();
    $value = AttributeValue::factory()->for($attribute)->create();
    $product = Product::factory()->for($blueprint)->create();

    $blueprint->attributes()->attach($attribute->id);
    $product->attributeValues()->attach($value->id);

    expect($product->attributeValues()->count())->toBe(1);

    $blueprint->attributes()->detach($attribute->id);

    expect($product->attributeValues()->count())->toBe(0);
});

it('cascades bulk detached blueprint attributes from related products', function () {
    $blueprint = Blueprint::factory()->create();
    $firstAttribute = Attribute::factory()->create();
    $secondAttribute = Attribute::factory()->create();
    $firstValue = AttributeValue::factory()->for($firstAttribute)->create();
    $secondValue = AttributeValue::factory()->for($secondAttribute)->create();
    $product = Product::factory()->for($blueprint)->create();

    $blueprint->attributes()->attach([$firstAttribute->id, $secondAttribute->id]);
    $product->attributeValues()->attach([$firstValue->id, $secondValue->id]);

    expect($product->attributeValues()->count())->toBe(2);

    $blueprint->attributes()->detach([$firstAttribute->id, $secondAttribute->id]);

    expect($product->attributeValues()->count())->toBe(0);
});

it('allows duplicate attribute codes when the unique attribute is attached', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create(['code' => 'colour']);

    $blueprint->attributes()->attach($attribute->id);

    expect($blueprint->allows('colour', 'colour'))->toBeTrue();
});

it('requires duplicate attribute codes when the unique attribute is required', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create(['code' => 'colour']);

    $blueprint->attributes()->attach($attribute->id, ['required' => true]);

    expect($blueprint->requires('colour', 'colour'))->toBeTrue();
});
