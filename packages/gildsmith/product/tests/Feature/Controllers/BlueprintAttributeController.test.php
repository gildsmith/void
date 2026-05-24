<?php

declare(strict_types=1);

use Gildsmith\Product\Controllers\BlueprintAttribute\BlueprintAttributeAttachController;
use Gildsmith\Product\Controllers\BlueprintAttribute\BlueprintAttributeDetachController;
use Gildsmith\Product\Controllers\BlueprintAttribute\BlueprintAttributeIndexController;
use Gildsmith\Product\Controllers\BlueprintAttribute\BlueprintAttributeUpdateController;
use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\AttributeValue;
use Gildsmith\Product\Models\Blueprint;
use Gildsmith\Product\Models\Product;
use Illuminate\Auth\Middleware\Authorize;

covers(BlueprintAttributeAttachController::class);
covers(BlueprintAttributeDetachController::class);
covers(BlueprintAttributeIndexController::class);
covers(BlueprintAttributeUpdateController::class);

beforeEach(function () {
    $this->withoutMiddleware(Authorize::class);
});

it('lists blueprint attributes with pivot data', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();

    $blueprint->attributes()->attach($attribute->id, ['required' => true]);

    $this->getJson("/blueprints/$blueprint->code/attributes")
        ->assertOk()
        ->assertJsonFragment([
            'code' => $attribute->code,
            'required' => true,
        ]);
});

it('attaches blueprint attributes through the api', function () {
    $blueprint = Blueprint::factory()->create();
    $product = Product::factory()->for($blueprint)->create();
    $attribute = Attribute::factory()->create();

    $this->postJson("/blueprints/$blueprint->code/attributes/$attribute->code", [
        'required' => true,
    ])->assertOk()
        ->assertJsonFragment([
            'code' => $attribute->code,
            'required' => true,
        ]);

    expect($blueprint->attributes()->where('code', $attribute->code)->exists())->toBeTrue()
        ->and($product->refresh()->is_complete)->toBeFalse();
});

it('updates blueprint attribute pivot data through the api', function () {
    $blueprint = Blueprint::factory()->create();
    $product = Product::factory()->for($blueprint)->create();
    $attribute = Attribute::factory()->create();

    $blueprint->attributes()->attach($attribute->id, ['required' => false]);

    $this->patchJson("/blueprints/$blueprint->code/attributes/$attribute->code", [
        'required' => true,
    ])->assertOk()
        ->assertJsonFragment([
            'code' => $attribute->code,
            'required' => true,
        ]);

    expect($blueprint->attributes()->where('code', $attribute->code)->first()->blueprintAttribute->required)->toBeTrue()
        ->and($product->refresh()->is_complete)->toBeFalse();
});

it('detaches blueprint attributes through the api and cascades product values', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();
    $value = AttributeValue::factory()->for($attribute)->create();
    $product = Product::factory()->for($blueprint)->create();

    $blueprint->attributes()->attach($attribute->id);
    $product->attributeValues()->attach($value->id);

    $this->deleteJson("/blueprints/$blueprint->code/attributes/$attribute->code")->assertOk()
        ->assertContent('1');

    expect($blueprint->attributes()->where('code', $attribute->code)->exists())->toBeFalse()
        ->and($product->attributeValues()->count())->toBe(0);
});
