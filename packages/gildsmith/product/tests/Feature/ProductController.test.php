<?php

declare(strict_types=1);

use Gildsmith\Product\Models\Product;

it('shows a product', function () {
    $product = Product::factory()->create();

    $response = $this->getJson("/products/{$product->code}");

    $response->assertOk()->assertJsonPath('code', $product->code);
});

it('returns 404 when product is missing', function () {
    $response = $this->getJson('/products/missing');

    $response->assertNotFound();
});

it('trashes a product', function () {
    $product = Product::factory()->create();

    $response = $this->postJson("/products/{$product->code}/trash");

    $response->assertOk();
    expect($response->json())->toEqual(true);
    $this->assertSoftDeleted('products', ['code' => $product->code]);
});

it('restores a product', function () {
    $product = Product::factory()->create();
    $this->postJson("/products/{$product->code}/trash");

    $response = $this->postJson("/products/{$product->code}/restore");

    $response->assertOk();
    $this->assertDatabaseHas('products', ['code' => $product->code, 'deleted_at' => null]);
});

it('deletes a product', function () {
    $product = Product::factory()->create();

    $response = $this->deleteJson("/products/{$product->code}");

    $response->assertOk();
    expect($response->json())->toEqual(true);
    $this->assertDatabaseMissing('products', ['code' => $product->code]);
});
