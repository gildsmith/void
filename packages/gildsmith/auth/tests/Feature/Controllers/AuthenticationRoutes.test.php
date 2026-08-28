<?php

declare(strict_types=1);

use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\Session;
use Gildsmith\Auth\Models\User;
use Tests\TestCase;

it('registers users with customer profiles and creates an API session', function () {
    /** @var TestCase $this */
    $response = $this->postJson('/auth/register', [
        'email' => 'new@example.com',
        'password' => 'password',
        'name' => 'Workbench',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonPath('user.email', 'new@example.com')
        ->assertJsonPath('session.name', 'Workbench')
        ->assertJsonPath('session.remember', false)
        ->assertJsonMissingPath('user.password')
        ->assertJsonMissingPath('session.token_hash')
        ->assertCookieMissing('laravel_session');

    $user = User::query()->where('email', 'new@example.com')->firstOrFail();

    expect($user->customer)->toBeInstanceOf(Customer::class);
    expect(Session::query()->where('user_id', $user->id)->count())->toBe(1);
    expect($response->json('token'))->toBeString();
});

it('creates short-lived API sessions by default', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $response = $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonPath('user.email', 'person@example.com')
        ->assertJsonPath('session.remember', false)
        ->assertJsonMissingPath('session.token_hash')
        ->assertCookieMissing('laravel_session');

    $session = Session::query()->firstOrFail();

    expect($session->remember)->toBeFalse();
    expect($session->expires_at->diffInMinutes(now()))->toBeLessThanOrEqual(120);
    expect($session->expires_at->isFuture())->toBeTrue();
});

it('supports multiple active remember sessions', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'remember@example.com',
        'password' => 'password',
    ]);

    $first = $this->postJson('/auth/sessions', [
        'email' => 'remember@example.com',
        'password' => 'password',
        'remember' => true,
        'name' => 'Laptop',
    ])->assertOk();

    $second = $this->postJson('/auth/sessions', [
        'email' => 'remember@example.com',
        'password' => 'password',
        'remember' => true,
        'name' => 'Phone',
    ])->assertOk();

    expect($first->json('token'))->not->toBe($second->json('token'));
    expect(Session::query()->active()->where('remember', true)->count())->toBe(2);
    expect(Session::query()->pluck('token_hash')->unique()->count())->toBe(2);
});

it('rejects invalid login attempts', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'wrong-password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('email');

    expect(Session::query()->count())->toBe(0);
});

it('authenticates API requests with bearer tokens', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $token = $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'password',
    ])->json('token');

    $this->withToken((string) $token)
        ->getJson('/auth/user')
        ->assertOk()
        ->assertJsonPath('user.email', 'person@example.com')
        ->assertJsonMissingPath('user.password');
});

it('lists active sessions for the authenticated user', function () {
    /** @var TestCase $this */
    $user = User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $firstToken = $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'password',
        'name' => 'Laptop',
    ])->json('token');

    $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'password',
        'name' => 'Phone',
    ]);

    User::factory()->create([
        'email' => 'other@example.com',
        'password' => 'password',
    ]);

    $this->postJson('/auth/sessions', [
        'email' => 'other@example.com',
        'password' => 'password',
        'name' => 'Other',
    ]);

    $this->withToken((string) $firstToken)
        ->getJson('/auth/sessions')
        ->assertOk()
        ->assertJsonCount(2, 'sessions')
        ->assertJsonPath('sessions.0.user_id', $user->id)
        ->assertJsonMissingPath('sessions.0.token_hash');
});

it('rejects unauthenticated API requests', function () {
    /** @var TestCase $this */
    $this->getJson('/auth/user')->assertUnauthorized();
    $this->getJson('/auth/sessions')->assertUnauthorized();
});

it('revokes the current session', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $token = $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'password',
    ])->json('token');

    $this->withToken((string) $token)
        ->deleteJson('/auth/sessions/current')
        ->assertNoContent();

    expect(Session::query()->count())->toBe(0);

    $this->withToken((string) $token)
        ->getJson('/auth/user')
        ->assertUnauthorized();
});

it('revokes owned sessions by id', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $token = $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'password',
        'name' => 'Current',
    ])->json('token');

    $otherSessionId = $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'password',
        'name' => 'Other',
    ])->json('session.id');

    $this->withToken((string) $token)
        ->deleteJson('/auth/sessions/' . $otherSessionId)
        ->assertNoContent();

    expect(Session::query()->find($otherSessionId))->toBeNull();
});

it('does not revoke sessions owned by another user', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);
    User::factory()->create([
        'email' => 'other@example.com',
        'password' => 'password',
    ]);

    $token = $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'password',
    ])->json('token');

    $otherSessionId = $this->postJson('/auth/sessions', [
        'email' => 'other@example.com',
        'password' => 'password',
    ])->json('session.id');

    $this->withToken((string) $token)
        ->deleteJson('/auth/sessions/' . $otherSessionId)
        ->assertNotFound();

    expect(Session::query()->find($otherSessionId))->not->toBeNull();
});

it('rejects expired bearer tokens', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $token = $this->postJson('/auth/sessions', [
        'email' => 'person@example.com',
        'password' => 'password',
    ])->json('token');

    Session::query()->firstOrFail()->forceFill([
        'expires_at' => now()->subMinute(),
    ])->save();

    $this->withToken((string) $token)
        ->getJson('/auth/user')
        ->assertUnauthorized();
});
