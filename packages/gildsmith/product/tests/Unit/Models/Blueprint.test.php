<?php

declare(strict_types=1);

use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\Blueprint;

covers(Blueprint::class);

it('returns true when attribute codes are allowed', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();
    $blueprint->attributes()->attach($attribute->id);

    expect($blueprint->allows($attribute->code))->toBeTrue();
});

it('returns false when attribute codes are not allowed', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();

    expect($blueprint->allows($attribute->code))->toBeFalse();
});

it('returns true when attribute codes are required', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();
    $blueprint->attributes()->attach($attribute->id, ['required' => true]);

    expect($blueprint->requires($attribute->code))->toBeTrue();
});

it('returns false when attribute codes are not required', function () {
    $blueprint = Blueprint::factory()->create();
    $attribute = Attribute::factory()->create();
    $blueprint->attributes()->attach($attribute->id, ['required' => false]);

    expect($blueprint->requires($attribute->code))->toBeFalse();
});
