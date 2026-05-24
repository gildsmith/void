<?php

declare(strict_types=1);

use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\AttributeValue;
use Gildsmith\Product\Models\Blueprint;
use Gildsmith\Product\Models\Product;
use Gildsmith\Product\Models\ProductCollection;
use Gildsmith\Product\Resources\AttributeResource;
use Gildsmith\Product\Resources\AttributeValueResource;
use Gildsmith\Product\Resources\BlueprintResource;
use Gildsmith\Product\Resources\ProductCollectionResource;
use Gildsmith\Product\Resources\ProductResource;

covers(AttributeResource::class);
covers(AttributeValueResource::class);
covers(BlueprintResource::class);
covers(ProductCollectionResource::class);
covers(ProductResource::class);

function resourceData(mixed $resource): array
{
    return json_decode($resource->response()->getContent(), true)['data'];
}

it('serializes products', function () {
    $blueprint = Blueprint::factory()->create([
        'code' => 'chair',
        'name' => ['en' => 'Chair', 'pl' => 'Krzeslo'],
    ]);

    $product = Product::factory()->for($blueprint)->create([
        'code' => 'wooden_chair',
        'name' => ['en' => 'Wooden chair', 'pl' => 'Drewniane krzeslo'],
    ]);

    $data = resourceData(ProductResource::make($product->load('blueprint')));

    expect($data)->toMatchArray([
        'code' => 'wooden_chair',
        'name' => ['en' => 'Wooden chair', 'pl' => 'Drewniane krzeslo'],
        'is_complete' => true,
        'created_at' => $product->created_at->getTimestamp(),
        'updated_at' => $product->updated_at->getTimestamp(),
        'deleted_at' => null,
        'blueprint' => [
            'code' => 'chair',
            'name' => ['en' => 'Chair', 'pl' => 'Krzeslo'],
            'deleted_at' => null,
        ],
    ]);
});

it('serializes attributes with blueprint pivot data', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create([
        'code' => 'colour',
        'name' => ['en' => 'Colour', 'pl' => 'Kolor'],
    ]);

    $blueprint->attributes()->attach($attribute->id, ['required' => true]);

    $data = resourceData(AttributeResource::make($blueprint->attributes()->first()));

    expect($data)->toMatchArray([
        'code' => 'colour',
        'name' => ['en' => 'Colour', 'pl' => 'Kolor'],
        'required' => true,
        'deleted_at' => null,
    ]);
});

it('serializes attribute values', function () {
    $attribute = Attribute::factory()->create([
        'code' => 'colour',
        'name' => ['en' => 'Colour', 'pl' => 'Kolor'],
    ]);
    $value = AttributeValue::factory()->for($attribute)->create([
        'code' => 'red',
        'name' => ['en' => 'Red', 'pl' => 'Czerwony'],
    ]);

    $data = resourceData(AttributeValueResource::make($value->load('attribute')));

    expect($data)->toMatchArray([
        'code' => 'red',
        'name' => ['en' => 'Red', 'pl' => 'Czerwony'],
        'deleted_at' => null,
        'attribute' => [
            'code' => 'colour',
            'name' => ['en' => 'Colour', 'pl' => 'Kolor'],
            'deleted_at' => null,
        ],
    ]);
});

it('serializes blueprints', function () {
    $blueprint = Blueprint::factory()->create([
        'code' => 'chair',
        'name' => ['en' => 'Chair', 'pl' => 'Krzeslo'],
    ]);
    $attribute = Attribute::factory()->create();

    $blueprint->attributes()->attach($attribute->id);

    $data = resourceData(BlueprintResource::make($blueprint->load('attributes')));

    expect($data)->toMatchArray([
        'code' => 'chair',
        'name' => ['en' => 'Chair', 'pl' => 'Krzeslo'],
        'deleted_at' => null,
    ])->and($data['attributes'])->toHaveCount(1);
});

it('serializes product collections', function () {
    $collection = ProductCollection::factory()->create([
        'code' => 'featured',
        'type' => 'manual',
        'name' => ['en' => 'Featured', 'pl' => 'Wyroznione'],
    ]);

    $data = resourceData(ProductCollectionResource::make($collection));

    expect($data)->toMatchArray([
        'code' => 'featured',
        'type' => 'manual',
        'name' => ['en' => 'Featured', 'pl' => 'Wyroznione'],
        'created_at' => $collection->created_at->getTimestamp(),
        'updated_at' => $collection->updated_at->getTimestamp(),
        'deleted_at' => null,
    ]);
});
