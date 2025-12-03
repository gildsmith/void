<?php

declare(strict_types=1);

use Gildsmith\Product\Models\Attribute;

it('lists attributes', function () {
    Attribute::factory()->count(3)->create();

    $response = $this->getJson('/attributes');

    $response->assertOk()->assertJsonCount(3);
});

it('creates an attribute', function () {
    $payload = [
        'code' => 'color',
        'name' => ['en' => 'Color', 'pl' => 'Kolor'],
    ];

    $response = $this->postJson('/attributes', $payload);

    $response->assertCreated()->assertJsonPath('code', 'color');
    $this->assertDatabaseHas('attributes', ['code' => 'color']);
});

it('shows an attribute', function () {
    $attribute = Attribute::factory()->create();

    $response = $this->getJson("/attributes/{$attribute->code}");

    $response->assertOk()->assertJsonPath('code', $attribute->code);
});

it('returns 404 when attribute is missing', function () {
    $response = $this->getJson('/attributes/missing');

    $response->assertNotFound();
});

it('updates an attribute', function () {
    $attribute = Attribute::factory()->create();

    $response = $this->putJson("/attributes/{$attribute->code}", [
        'name' => ['en' => 'Updated', 'pl' => 'Zaktualizowany'],
    ]);

    $response->assertOk()->assertJsonPath('name.en', 'Updated');
    $this->assertDatabaseHas('attributes', ['code' => $attribute->code, 'name->en' => 'Updated']);
});

it('trashes an attribute', function () {
    $attribute = Attribute::factory()->create();

    $response = $this->postJson("/attributes/{$attribute->code}/trash");

    $response->assertOk();
    expect($response->json())->toEqual(true);
    $this->assertSoftDeleted('attributes', ['code' => $attribute->code]);
});

it('restores an attribute', function () {
    $attribute = Attribute::factory()->create();
    $this->postJson("/attributes/{$attribute->code}/trash");

    $response = $this->postJson("/attributes/{$attribute->code}/restore");

    $response->assertOk();
    $this->assertDatabaseHas('attributes', ['code' => $attribute->code, 'deleted_at' => null]);
});

it('deletes an attribute', function () {
    $attribute = Attribute::factory()->create();

    $response = $this->deleteJson("/attributes/{$attribute->code}");

    $response->assertOk();
    expect($response->json())->toEqual(true);
    $this->assertDatabaseMissing('attributes', ['code' => $attribute->code]);
});
