<?php

declare(strict_types=1);

use Gildsmith\Product\Models\Product;
use Gildsmith\Product\Models\ProductCollection;
use Illuminate\Auth\Middleware\Authorize;

beforeEach(function () {
    $this->withoutMiddleware(Authorize::class);
});

it('validates create payloads through model rules', function () {
    $this->postJson('/products', [
        'code' => 'Invalid Code',
        'name' => ['en' => 'Chair'],
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('code');

    expect(Product::query()->count())->toBe(0);
});

it('keeps explicit model rules in form requests', function () {
    $this->postJson('/collections', [
        'code' => 'summer',
        'name' => ['en' => 'Summer'],
        'type' => 'seasonal sale',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('type');

    expect(ProductCollection::query()->count())->toBe(0);
});

it('validates blueprint attribute pivot payloads', function () {
    $this->patchJson('/blueprints/chair/attributes/colour', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('required');
});
