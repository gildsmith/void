<?php

declare(strict_types=1);

use Gildsmith\Auth\Facades\UserFacade;
use Gildsmith\Auth\Models\Customer;
use Gildsmith\Auth\Models\Employee;
use Gildsmith\Auth\Models\User;
use Gildsmith\Contract\Auth\Facades\UserFacadeInterface;
use Gildsmith\Support\Exceptions\ImmutableAttributeException;
use Gildsmith\Support\Facades\User as UserSupportFacade;
use Illuminate\Support\Facades\Hash;

covers(UserFacade::class);

describe('binding', function () {
    it('binds the user facade contract', function () {
        expect(resolve(UserFacadeInterface::class))->toBeInstanceOf(UserFacade::class);
        expect(UserSupportFacade::getFacadeRoot())->toBeInstanceOf(UserFacadeInterface::class);
    });
});

describe('create', function () {
    it('creates users with a stable email code and hashed password', function () {
        $facade = resolve(UserFacadeInterface::class);

        $user = $facade->create([
            'email' => 'person@example.com',
            'password' => 'password',
        ]);

        expect($user)->toBeInstanceOf(User::class);
        expect($user->code)->toBe('person@example.com');
        expect(Hash::check('password', $user->password))->toBeTrue();
    });
});

describe('register', function () {
    it('creates the matching customer profile', function () {
        $facade = resolve(UserFacadeInterface::class);

        $user = $facade->register([
            'email' => 'customer@example.com',
            'password' => 'password',
        ]);

        expect($user)->toBeInstanceOf(User::class);
        expect($user->customer)->toBeInstanceOf(Customer::class);
    });
});

describe('login', function () {
    it('returns the user and updates the last login timestamp for valid credentials', function () {
        $facade = resolve(UserFacadeInterface::class);
        $user = User::factory()->create([
            'email' => 'person@example.com',
            'password' => 'password',
        ]);

        $loggedIn = $facade->login('person@example.com', 'password');

        expect($loggedIn?->is($user))->toBeTrue();
        expect($loggedIn?->last_login_at)->not->toBeNull();
    });

    it('returns null for invalid or unknown credentials', function () {
        $facade = resolve(UserFacadeInterface::class);
        User::factory()->create([
            'email' => 'person@example.com',
            'password' => 'password',
        ]);

        expect($facade->login('person@example.com', 'wrong-password'))->toBeNull();
        expect($facade->login('missing@example.com', 'password'))->toBeNull();
    });
});

describe('grantEmployeeAccess', function () {
    it('creates employee access once', function () {
        $facade = resolve(UserFacadeInterface::class);
        $user = User::factory()->create();

        $employee = $facade->grantEmployeeAccess($user);
        $repeated = $facade->grantEmployeeAccess($user);

        expect($employee)->toBeInstanceOf(Employee::class);
        expect($repeated->is($employee))->toBeTrue();
        expect(Employee::query()->where('user_id', $user->id)->count())->toBe(1);
        expect($user->refresh()->hasEmployeeAccess())->toBeTrue();
    });

    it('restores previously revoked employee access', function () {
        $facade = resolve(UserFacadeInterface::class);
        $user = User::factory()->create();
        $employee = Employee::factory()->create(['user_id' => $user->id]);
        $employee->delete();

        $restored = $facade->grantEmployeeAccess($user);

        expect($restored->is($employee))->toBeTrue();
        expect($restored->trashed())->toBeFalse();
        expect($user->refresh()->hasEmployeeAccess())->toBeTrue();
    });
});

describe('revokeEmployeeAccess', function () {
    it('soft deletes existing employee access', function () {
        $facade = resolve(UserFacadeInterface::class);
        $user = User::factory()->create();
        Employee::factory()->create(['user_id' => $user->id]);

        expect($facade->revokeEmployeeAccess($user))->toBeTrue();
        expect($user->refresh()->hasEmployeeAccess())->toBeFalse();
        expect(Employee::withTrashed()->where('user_id', $user->id)->count())->toBe(1);
    });

    it('returns false when employee access does not exist', function () {
        $facade = resolve(UserFacadeInterface::class);
        $user = User::factory()->create();

        expect($facade->revokeEmployeeAccess($user))->toBeFalse();
    });
});

describe('all', function () {
    it('excludes trashed users by default and includes them on request', function () {
        $facade = resolve(UserFacadeInterface::class);
        User::factory()->create(['email' => 'active@example.com']);
        User::factory()->create(['email' => 'trashed@example.com'])->delete();

        expect($facade->all()->pluck('email')->all())->toBe(['active@example.com']);
        expect($facade->all(true)->pluck('email')->all())->toContain('active@example.com', 'trashed@example.com');
    });
});

describe('find', function () {
    it('finds users by email code', function () {
        $facade = resolve(UserFacadeInterface::class);
        $user = User::factory()->create(['email' => 'person@example.com']);

        $found = $facade->find('person@example.com');

        expect($found?->is($user))->toBeTrue();
    });

    it('returns null for missing and hidden trashed users', function () {
        $facade = resolve(UserFacadeInterface::class);
        $trashed = User::factory()->create(['email' => 'trashed@example.com']);
        $trashed->delete();

        expect($facade->find('missing@example.com'))->toBeNull();
        expect($facade->find('trashed@example.com'))->toBeNull();
        expect($facade->find('trashed@example.com', true)?->is($trashed))->toBeTrue();
    });
});

describe('trashed', function () {
    it('returns only soft-deleted users', function () {
        $facade = resolve(UserFacadeInterface::class);
        User::factory()->create(['email' => 'active@example.com']);
        User::factory()->create(['email' => 'trashed@example.com'])->delete();

        expect($facade->trashed()->pluck('email')->all())->toBe(['trashed@example.com']);
    });
});

describe('update', function () {
    it('updates users by email code', function () {
        $facade = resolve(UserFacadeInterface::class);
        User::factory()->create(['email' => 'person@example.com']);

        $updated = $facade->update('person@example.com', [
            'last_login_at' => now(),
        ]);

        expect($updated)->toBeInstanceOf(User::class);
        expect($updated?->last_login_at)->not->toBeNull();
    });

    it('returns null when the user does not exist', function () {
        $facade = resolve(UserFacadeInterface::class);

        expect($facade->update('missing@example.com', ['last_login_at' => now()]))->toBeNull();
    });

    it('rejects changes to the email code', function () {
        $facade = resolve(UserFacadeInterface::class);
        User::factory()->create(['email' => 'person@example.com']);

        expect(fn() => $facade->update('person@example.com', [
            'email' => 'changed@example.com',
        ]))->toThrow(ImmutableAttributeException::class);
    });
});

describe('updateOrCreate', function () {
    it('creates missing users by email code', function () {
        $facade = resolve(UserFacadeInterface::class);

        $created = $facade->updateOrCreate('person@example.com', [
            'password' => 'password',
        ]);

        expect($created)->toBeInstanceOf(User::class);
        expect($created->email)->toBe('person@example.com');
    });

    it('updates existing users without duplicating them', function () {
        $facade = resolve(UserFacadeInterface::class);
        $created = User::factory()->create(['email' => 'person@example.com']);

        $updated = $facade->updateOrCreate('person@example.com', [
            'last_login_at' => now(),
        ]);

        expect($updated->is($created))->toBeTrue();
        expect($updated->last_login_at)->not->toBeNull();
        expect(User::query()->where('email', 'person@example.com')->count())->toBe(1);
    });

    it('rejects conflicting email data', function () {
        $facade = resolve(UserFacadeInterface::class);
        User::factory()->create(['email' => 'person@example.com']);

        expect(fn() => $facade->updateOrCreate('person@example.com', [
            'email' => 'changed@example.com',
        ]))->toThrow(ImmutableAttributeException::class);
    });
});

describe('delete', function () {
    it('soft deletes by default and force deletes on request', function () {
        $facade = resolve(UserFacadeInterface::class);
        $user = User::factory()->create(['email' => 'person@example.com']);

        expect($facade->delete('person@example.com'))->toBeTrue();
        expect(User::query()->whereKey($user->getKey())->exists())->toBeFalse();
        expect(User::withTrashed()->whereKey($user->getKey())->exists())->toBeTrue();

        expect($facade->delete('person@example.com', true))->toBeTrue();
        expect(User::withTrashed()->whereKey($user->getKey())->exists())->toBeFalse();
    });

    it('returns false when the user does not exist', function () {
        $facade = resolve(UserFacadeInterface::class);

        expect($facade->delete('missing@example.com'))->toBeFalse();
    });
});

describe('restore', function () {
    it('restores a soft-deleted user', function () {
        $facade = resolve(UserFacadeInterface::class);
        $user = User::factory()->create(['email' => 'person@example.com']);
        $user->delete();

        expect($facade->restore('person@example.com'))->toBeTrue();
        expect(User::query()->whereKey($user->getKey())->exists())->toBeTrue();
    });

    it('returns false when the user does not exist', function () {
        $facade = resolve(UserFacadeInterface::class);

        expect($facade->restore('missing@example.com'))->toBeFalse();
    });
});
