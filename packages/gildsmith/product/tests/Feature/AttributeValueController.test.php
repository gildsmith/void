<?php

declare(strict_types=1);

use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\AttributeValue;

it('lists attribute values', function () {
    $attribute = Attribute::factory()->create();
    AttributeValue::factory()->count(3)->for($attribute)->create();

    $response = $this->getJson("/attributes/{$attribute->code}/values");

    $response->assertOk()->assertJsonCount(3);
});

it('creates an attribute value', function () {
    $attribute = Attribute::factory()->create();

    $payload = [
        'code' => 'red',
        'name' => ['en' => 'Red', 'pl' => 'Czerwony'],
    ];

    $response = $this->postJson("/attributes/{$attribute->code}/values", $payload);

    $response->assertCreated()->assertJsonPath('code', 'red');
    $this->assertDatabaseHas('attribute_values', ['code' => 'red', 'attribute_id' => $attribute->id]);
});

it('shows an attribute value', function () {
    $value = AttributeValue::factory()->for(Attribute::factory())->create();

    $response = $this->getJson("/attributes/{$value->attribute->code}/values/{$value->code}");

    $response->assertOk()->assertJsonPath('code', $value->code);
});

it('returns 404 when attribute is missing', function () {
    $response = $this->getJson('/attributes/missing/values/any');

    $response->assertNotFound();
});

it('returns 404 when attribute value is missing', function () {
    $attribute = Attribute::factory()->create();

    $response = $this->getJson("/attributes/{$attribute->code}/values/missing");

    $response->assertNotFound();
});

it('updates an attribute value', function () {
    $value = AttributeValue::factory()->for(Attribute::factory())->create();

    $response = $this->putJson("/attributes/{$value->attribute->code}/values/{$value->code}", [
        'name' => ['en' => 'Updated', 'pl' => 'Zaktualizowany'],
    ]);

    $response->assertOk()->assertJsonPath('name.en', 'Updated');
    $this->assertDatabaseHas('attribute_values', ['code' => $value->code, 'name->en' => 'Updated']);
});

it('trashes an attribute value', function () {
    $value = AttributeValue::factory()->for(Attribute::factory())->create();

    $response = $this->postJson("/attributes/{$value->attribute->code}/values/{$value->code}/trash");

    $response->assertOk();
    expect($response->json())->toEqual(true);
    $this->assertSoftDeleted('attribute_values', ['code' => $value->code]);
});

it('restores an attribute value', function () {
    $value = AttributeValue::factory()->for(Attribute::factory())->create();
    $this->postJson("/attributes/{$value->attribute->code}/values/{$value->code}/trash");

    $response = $this->postJson("/attributes/{$value->attribute->code}/values/{$value->code}/restore");

    $response->assertOk();
    $this->assertDatabaseHas('attribute_values', ['code' => $value->code, 'deleted_at' => null]);
});

it('deletes an attribute value', function () {
    $value = AttributeValue::factory()->for(Attribute::factory())->create();

    $response = $this->deleteJson("/attributes/{$value->attribute->code}/values/{$value->code}");

    $response->assertOk();
    expect($response->json())->toEqual(true);
    $this->assertDatabaseMissing('attribute_values', ['code' => $value->code]);
});
