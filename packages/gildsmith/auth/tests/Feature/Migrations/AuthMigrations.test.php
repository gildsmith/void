<?php

declare(strict_types=1);

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

it('creates auth and profile tables', function () {
    expect(Schema::hasTable('users'))->toBeTrue();
    expect(Schema::hasTable('customers'))->toBeTrue();
    expect(Schema::hasTable('employees'))->toBeTrue();

    foreach (['email', 'email_verified_at', 'password', 'remember_token', 'last_login_at', 'deleted_at', 'created_at', 'updated_at'] as $column) {
        expect(Schema::hasColumn('users', $column))->toBeTrue();
    }

    foreach (['customers', 'employees'] as $table) {
        expect(Schema::hasColumn($table, 'user_id'))->toBeTrue();
        expect(Schema::hasColumn($table, 'deleted_at'))->toBeTrue();
        expect(Schema::hasColumn($table, 'created_at'))->toBeTrue();
        expect(Schema::hasColumn($table, 'updated_at'))->toBeTrue();
    }
});

it('requires unique customer and employee profiles per user', function () {
    $userId = DB::table('users')->insertGetId([
        'email' => 'person@example.com',
        'password' => 'password',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('customers')->insert([
        'user_id' => $userId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('employees')->insert([
        'user_id' => $userId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    expect(fn () => DB::table('customers')->insert([
        'user_id' => $userId,
        'created_at' => now(),
        'updated_at' => now(),
    ]))->toThrow(QueryException::class);

    expect(fn () => DB::table('employees')->insert([
        'user_id' => $userId,
        'created_at' => now(),
        'updated_at' => now(),
    ]))->toThrow(QueryException::class);
});

it('requires customer and employee users to exist', function () {
    expect(fn () => DB::table('customers')->insert([
        'user_id' => 999,
        'created_at' => now(),
        'updated_at' => now(),
    ]))->toThrow(QueryException::class);

    expect(fn () => DB::table('employees')->insert([
        'user_id' => 999,
        'created_at' => now(),
        'updated_at' => now(),
    ]))->toThrow(QueryException::class);
});
