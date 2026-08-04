<?php

declare(strict_types=1);

use Gildsmith\Auth\Facades\UserFacade;
use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\Employee;
use Gildsmith\Auth\Models\User;
use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Illuminate\Support\Facades\Hash;

it('binds the user facade contract', function () {
    expect(resolve(UserFacadeInterface::class))->toBeInstanceOf(UserFacade::class);
});

it('creates and finds users by email', function () {
    $facade = resolve(UserFacadeInterface::class);

    $user = $facade->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->getCode())->toBe('person@example.com');
    expect(Hash::check('password', $user->password))->toBeTrue();
    expect($facade->find('person@example.com')?->is($user))->toBeTrue();
});

it('registers users with customer actors', function () {
    $facade = resolve(UserFacadeInterface::class);

    $user = $facade->register([
        'email' => 'customer@example.com',
        'password' => 'password',
    ]);

    expect($user)->toBeInstanceOf(User::class);
    expect($user->customer)->toBeInstanceOf(Customer::class);
});

it('logs users in and updates last login timestamp', function () {
    $facade = resolve(UserFacadeInterface::class);
    $user = User::factory()->create([
        'email' => 'person@example.com',
        'password' => 'password',
    ]);

    $loggedIn = $facade->login('person@example.com', 'password');

    expect($loggedIn?->is($user))->toBeTrue();
    expect($loggedIn?->last_login_at)->not->toBeNull();
    expect($facade->login('person@example.com', 'wrong-password'))->toBeNull();
    expect($facade->login('missing@example.com', 'password'))->toBeNull();
});

it('grants and revokes employee access', function () {
    $facade = resolve(UserFacadeInterface::class);
    $user = User::factory()->create();

    $employee = $facade->grantEmployeeAccess($user);

    expect($employee)->toBeInstanceOf(Employee::class);
    expect($user->refresh()->hasEmployeeAccess())->toBeTrue();
    expect($facade->grantEmployeeAccess($user)->is($employee))->toBeTrue();
    expect(Employee::query()->where('user_id', $user->id)->count())->toBe(1);

    expect($facade->revokeEmployeeAccess($user))->toBeTrue();
    expect($user->refresh()->hasEmployeeAccess())->toBeFalse();
    expect(Employee::withTrashed()->where('user_id', $user->id)->count())->toBe(1);

    $restored = $facade->grantEmployeeAccess($user);

    expect($restored->is($employee))->toBeTrue();
    expect($restored->trashed())->toBeFalse();
    expect($user->refresh()->hasEmployeeAccess())->toBeTrue();
    expect($facade->revokeEmployeeAccess(User::factory()->create()))->toBeFalse();
});

it('issues and revokes sanctum tokens', function () {
    $facade = resolve(UserFacadeInterface::class);
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $token = $facade->issueToken($user);
    $otherToken = $facade->issueToken($otherUser);

    expect($user->tokens()->count())->toBe(1);
    expect($facade->logout($otherUser, $token))->toBeFalse();
    expect($facade->logout($user, $otherToken))->toBeFalse();
    expect($facade->logout($user, $token))->toBeTrue();
    expect($facade->logout($user, $token))->toBeFalse();
    expect($user->tokens()->count())->toBe(0);
});

it('revokes every sanctum token for a user', function () {
    $facade = resolve(UserFacadeInterface::class);
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $facade->issueToken($user);
    $facade->issueToken($user);
    $facade->issueToken($otherUser);

    expect($facade->logoutEverywhere($user))->toBeTrue();
    expect($user->tokens()->count())->toBe(0);
    expect($otherUser->tokens()->count())->toBe(1);
});

it('lists users without trashed users by default', function () {
    $facade = resolve(UserFacadeInterface::class);

    User::factory()->create(['email' => 'active@example.com']);
    User::factory()->create(['email' => 'trashed@example.com'])->delete();

    expect($facade->all()->pluck('email')->all())->toBe(['active@example.com']);
    expect($facade->all(true)->pluck('email')->all())->toContain('active@example.com', 'trashed@example.com');
    expect($facade->trashed()->pluck('email')->all())->toBe(['trashed@example.com']);
});

it('updates users by email', function () {
    $facade = resolve(UserFacadeInterface::class);
    User::factory()->create(['email' => 'person@example.com']);

    $updated = $facade->update('person@example.com', [
        'last_login_at' => now(),
    ]);

    expect($updated)->toBeInstanceOf(User::class);
    expect($updated?->last_login_at)->not->toBeNull();
    expect($facade->update('missing@example.com', ['last_login_at' => now()]))->toBeNull();
});

it('updates or creates users by email', function () {
    $facade = resolve(UserFacadeInterface::class);

    $created = $facade->updateOrCreate('person@example.com', [
        'password' => 'password',
    ]);

    expect($created)->toBeInstanceOf(User::class);
    expect($created->email)->toBe('person@example.com');

    $updated = $facade->updateOrCreate('person@example.com', [
        'last_login_at' => now(),
    ]);

    expect($updated->is($created))->toBeTrue();
    expect($updated->last_login_at)->not->toBeNull();
});

it('soft deletes, restores, and force deletes users by email', function () {
    $facade = resolve(UserFacadeInterface::class);
    User::factory()->create(['email' => 'person@example.com']);

    expect($facade->delete('person@example.com'))->toBeTrue();
    expect($facade->find('person@example.com'))->toBeNull();
    expect($facade->find('person@example.com', true))->toBeInstanceOf(User::class);

    expect($facade->restore('person@example.com'))->toBeTrue();
    expect($facade->find('person@example.com'))->toBeInstanceOf(User::class);

    expect($facade->delete('person@example.com', true))->toBeTrue();
    expect($facade->find('person@example.com', true))->toBeNull();
    expect($facade->delete('missing@example.com'))->toBeFalse();
});
