<?php

declare(strict_types=1);

use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\Employee;
use Gildsmith\Auth\Models\User;
use Gildsmith\Contract\User\CustomerInterface;
use Gildsmith\Contract\User\EmployeeInterface;
use Gildsmith\Contract\User\UserInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

it('binds auth contracts to auth models', function () {
    expect(resolve(UserInterface::class))->toBeInstanceOf(User::class);
    expect(resolve(CustomerInterface::class))->toBeInstanceOf(Customer::class);
    expect(resolve(EmployeeInterface::class))->toBeInstanceOf(Employee::class);
});

it('allows one user to have customer and employee profiles', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->for($user)->create();
    $employee = Employee::factory()->for($user)->create();

    expect($user->customer)->toBeInstanceOf(Customer::class);
    expect($user->employee)->toBeInstanceOf(Employee::class);
    expect($user->customer->is($customer))->toBeTrue();
    expect($user->employee->is($employee))->toBeTrue();
});

it('belongs customer and employee profiles to their user', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->for($user)->create();
    $employee = Employee::factory()->for($user)->create();

    expect($customer->user)->toBeInstanceOf(User::class);
    expect($employee->user)->toBeInstanceOf(User::class);
    expect($customer->user->is($user))->toBeTrue();
    expect($employee->user->is($user))->toBeTrue();
});

it('hashes user passwords', function () {
    $user = User::factory()->create([
        'password' => 'secret',
    ]);

    expect($user->password)->not->toBe('secret');
    expect(Hash::check('secret', $user->password))->toBeTrue();
});

it('validates users when they are created', function () {
    expect(fn () => User::create([
        'email' => 'invalid-email',
        'password' => 'password',
    ]))->toThrow(ValidationException::class);
});

it('creates sanctum personal access tokens for users', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token');

    expect($token->accessToken)->toBeInstanceOf(PersonalAccessToken::class);
    expect($token->plainTextToken)->toBeString();
});

it('soft deletes auth models', function () {
    $user = User::factory()->create();
    $customer = Customer::factory()->for($user)->create();
    $employee = Employee::factory()->for($user)->create();

    $customer->delete();
    $employee->delete();
    $user->delete();

    expect(Customer::find($customer->id))->toBeNull();
    expect(Employee::find($employee->id))->toBeNull();
    expect(User::find($user->id))->toBeNull();
    expect(Customer::withTrashed()->find($customer->id))->not->toBeNull();
    expect(Employee::withTrashed()->find($employee->id))->not->toBeNull();
    expect(User::withTrashed()->find($user->id))->not->toBeNull();
});
