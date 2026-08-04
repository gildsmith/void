<?php

declare(strict_types=1);

use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\Employee;
use Gildsmith\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

it('registers a user and returns a bearer token', function () {
    /** @var TestCase $this */
    $response = $this->postJson('/auth/register', [
        'email' => 'customer@example.com',
        'password' => 'password',
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonPath('user.email', 'customer@example.com')
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => ['id', 'email'],
        ]);

    $user = User::query()->where('email', 'customer@example.com')->firstOrFail();

    expect($user->customer)->not->toBeNull();
    expect(Hash::check('password', $user->password))->toBeTrue();

    expect(DB::table('personal_access_tokens')->where([
        'name' => 'api',
        'tokenable_type' => User::class,
        'tokenable_id' => $user->id,
    ])->exists())->toBeTrue();
});

it('validates registration payloads', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'taken@example.com',
    ]);

    $this->postJson('/auth/register', [
        'email' => 'taken@example.com',
        'password' => 'short',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

it('logs a user in and returns a bearer token', function () {
    /** @var TestCase $this */
    $user = User::factory()->create([
        'email' => 'employee@example.com',
        'password' => 'password',
    ]);

    $response = $this->postJson('/auth/login', [
        'email' => 'employee@example.com',
        'password' => 'password',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonPath('user.email', 'employee@example.com')
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => ['id', 'email'],
        ]);

    expect($user->refresh()->last_login_at)->not->toBeNull();

    expect(DB::table('personal_access_tokens')->where([
        'name' => 'api',
        'tokenable_type' => User::class,
        'tokenable_id' => $user->id,
    ])->exists())->toBeTrue();
});

it('returns the authenticated user', function () {
    /** @var TestCase $this */
    $user = User::factory()->create([
        'email' => 'person@example.com',
    ]);
    $customer = Customer::factory()->for($user)->create();
    $employee = Employee::factory()->for($user)->create();
    $token = $user->createToken('api')->plainTextToken;

    $this->withToken($token)
        ->getJson('/auth/me')
        ->assertOk()
        ->assertJsonPath('id', $user->id)
        ->assertJsonPath('email', 'person@example.com')
        ->assertJsonPath('customer.id', $customer->id)
        ->assertJsonPath('employee.id', $employee->id)
        ->assertJsonStructure([
            'id',
            'email',
            'email_verified_at',
            'last_login_at',
            'customer' => [
                'id',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            'employee' => [
                'id',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            'created_at',
            'updated_at',
            'deleted_at',
        ]);
});

it('requires authentication for current user and logout endpoints', function () {
    /** @var TestCase $this */
    $this->getJson('/auth/me')->assertUnauthorized();
    $this->postJson('/auth/logout')->assertUnauthorized();
    $this->postJson('/auth/logout-everywhere')->assertUnauthorized();
});

it('logs out the current token only', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $currentToken = $user->createToken('api')->plainTextToken;
    $otherToken = $user->createToken('api')->plainTextToken;

    $this->withToken($currentToken)
        ->postJson('/auth/logout')
        ->assertNoContent();

    expect($user->tokens()->count())->toBe(1);

    Auth::forgetGuards();

    $this->withToken($currentToken)
        ->getJson('/auth/me')
        ->assertUnauthorized();

    Auth::forgetGuards();

    $this->withToken($otherToken)
        ->getJson('/auth/me')
        ->assertOk();
});

it('logs out every token for the authenticated user', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $currentToken = $user->createToken('api')->plainTextToken;
    $otherToken = $user->createToken('api')->plainTextToken;
    $otherUserToken = $otherUser->createToken('api')->plainTextToken;

    $this->withToken($currentToken)
        ->postJson('/auth/logout-everywhere')
        ->assertNoContent();

    expect($user->tokens()->count())->toBe(0);
    expect($otherUser->tokens()->count())->toBe(1);

    Auth::forgetGuards();

    $this->withToken($currentToken)
        ->getJson('/auth/me')
        ->assertUnauthorized();

    Auth::forgetGuards();

    $this->withToken($otherToken)
        ->getJson('/auth/me')
        ->assertUnauthorized();

    Auth::forgetGuards();

    $this->withToken($otherUserToken)
        ->getJson('/auth/me')
        ->assertOk();
});

it('rejects invalid login credentials', function () {
    /** @var TestCase $this */
    User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $this->postJson('/auth/login', [
        'email' => 'person@example.com',
        'password' => 'wrong-password',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});
