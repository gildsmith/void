<?php

declare(strict_types=1);

use Gildsmith\Product\Models\Blueprint;

it('lists blueprints', function () {
    Blueprint::factory()->count(3)->create();

    $response = $this->getJson('/blueprints');

    $response->assertOk()->assertJsonCount(3);
});

it('creates a blueprint', function () {
    $payload = [
        'code' => 'test_blueprint',
        'name' => ['en' => 'Test', 'pl' => 'Test'],
    ];

    $response = $this->postJson('/blueprints', $payload);

    $response->assertCreated()->assertJsonPath('code', 'test_blueprint');
    $this->assertDatabaseHas('blueprints', ['code' => 'test_blueprint']);
});

it('shows a blueprint', function () {
    $blueprint = Blueprint::factory()->create();

    $response = $this->getJson("/blueprints/{$blueprint->code}");

    $response->assertOk()->assertJsonPath('code', $blueprint->code);
});

it('updates a blueprint', function () {
    $blueprint = Blueprint::factory()->create();

    $response = $this->putJson("/blueprints/{$blueprint->code}", [
        'name' => ['en' => 'Updated', 'pl' => 'Zaktualizowany'],
    ]);

    $response->assertOk()->assertJsonPath('name.en', 'Updated');
    $this->assertDatabaseHas('blueprints', ['code' => $blueprint->code, 'name->en' => 'Updated']);
});

it('trashes a blueprint', function () {
    $blueprint = Blueprint::factory()->create();

    $response = $this->postJson("/blueprints/{$blueprint->code}/trash");

    $response->assertOk();
    expect($response->json())->toEqual(true);
    $this->assertSoftDeleted('blueprints', ['code' => $blueprint->code]);
});

it('restores a blueprint', function () {
    $blueprint = Blueprint::factory()->create();
    $this->postJson("/blueprints/{$blueprint->code}/trash");

    $response = $this->postJson("/blueprints/{$blueprint->code}/restore");

    $response->assertOk();
    $this->assertDatabaseHas('blueprints', ['code' => $blueprint->code, 'deleted_at' => null]);
});

it('deletes a blueprint', function () {
    $blueprint = Blueprint::factory()->create();

    $response = $this->deleteJson("/blueprints/{$blueprint->code}");

    $response->assertOk();
    expect($response->json())->toEqual(true);
    $this->assertDatabaseMissing('blueprints', ['code' => $blueprint->code]);
});
