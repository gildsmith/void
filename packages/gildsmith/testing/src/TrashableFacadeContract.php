<?php

declare(strict_types=1);

use Gildsmith\Contract\Facades\TrashableFacadeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @param  class-string<Facade>  $facade
 * @param  class-string<Model>  $model
 * @param  array<string, mixed>  $createData
 * @param  array<string, mixed>  $updateData
 */
function itFulfillsTrashableFacadeContract(
    string $facade,
    string $model,
    array $createData = [],
    array $updateData = [],
): void {
    itFulfillsCrudFacadeContract(
        facade: $facade,
        model: $model,
        createData: $createData,
        updateData: $updateData,
    );

    describe($facade, function () use ($facade, $model, $createData) {
        describe('configuration', function () use ($facade, $model) {
            it('receives a valid trashable facade', function () use ($facade) {
                expect(is_subclass_of($facade, Facade::class))->toBeTrue();
                expect($facade::getFacadeRoot())->toBeInstanceOf(TrashableFacadeInterface::class);
            });

            it('receives a model using soft deletes', function () use ($model) {
                expect(in_array(SoftDeletes::class, class_uses_recursive($model), true))->toBeTrue();
            });
        });

        describe('all', function () use ($facade, $model, $createData) {
            it('excludes trashed records by default', function () use ($facade, $model, $createData) {
                $active = $model::factory()->create(array_merge($createData, [
                    'code' => 'active-record',
                ]));

                $trashed = $model::factory()->create(array_merge($createData, [
                    'code' => 'trashed-record',
                ]));

                $trashed->delete();

                $result = $facade::all();
                $codes = $result->pluck('code')->all();

                expect($result)->toBeInstanceOf(Collection::class);
                expect($result)->toHaveCount(1);
                expect($codes)->toContain($active->code);
                expect(in_array($trashed->code, $codes, true))->toBeFalse();
            });

            it('includes trashed records when requested', function () use ($facade, $model, $createData) {
                $active = $model::factory()->create(array_merge($createData, [
                    'code' => 'active-record',
                ]));

                $trashed = $model::factory()->create(array_merge($createData, [
                    'code' => 'trashed-record',
                ]));

                $trashed->delete();

                $result = $facade::all(true);
                $codes = $result->pluck('code')->all();

                expect($result)->toBeInstanceOf(Collection::class);
                expect($result)->toHaveCount(2);
                expect($codes)->toContain($active->code);
                expect($codes)->toContain($trashed->code);
            });

            it('returns model instances when trashed records are included', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create($createData);

                $record->delete();

                $result = $facade::all(true);

                expect($result->first())->toBeInstanceOf($model);
            });
        });

        describe('find', function () use ($facade, $model, $createData) {
            it('excludes trashed records by default', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-find',
                ]));

                $record->delete();

                $result = $facade::find('record-to-find');

                expect($result)->toBeNull();
            });

            it('finds trashed records when requested', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-find',
                ]));

                $record->delete();

                $result = $facade::find('record-to-find', true);

                expect($result)->toBeInstanceOf($model);
                expect($result->getKey())->toBe($record->getKey());
                expect($result->trashed())->toBeTrue();
            });

            it('finds trashed records by code instead of database id when requested', function () use ($facade, $model, $createData) {
                $first = $model::factory()->create(array_merge($createData, [
                    'code' => 'first-code',
                ]));

                $second = $model::factory()->create(array_merge($createData, [
                    'code' => (string) $first->getKey(),
                ]));

                $second->delete();

                $result = $facade::find((string) $first->getKey(), true);

                expect($result)->toBeInstanceOf($model);
                expect($result->getKey())->toBe($second->getKey());
            });

            it('returns null when trashed record does not exist', function () use ($facade) {
                $result = $facade::find((string) str()->uuid(), true);

                expect($result)->toBeNull();
            });
        });

        describe('delete', function () use ($facade, $model, $createData) {
            it('soft deletes an existing record by default', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-delete',
                ]));

                $result = $facade::delete('record-to-delete');

                expect($result)->toBeTrue();
                expect($model::query()->whereKey($record->getKey())->exists())->toBeFalse();
                expect($model::withTrashed()->whereKey($record->getKey())->exists())->toBeTrue();
                expect($model::withTrashed()->whereKey($record->getKey())->first()->trashed())->toBeTrue();
            });

            it('force deletes an active record when requested', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-force-delete',
                ]));

                $result = $facade::delete('record-to-force-delete', true);

                expect($result)->toBeTrue();
                expect($model::withTrashed()->whereKey($record->getKey())->exists())->toBeFalse();
            });

            it('force deletes a trashed record when requested', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'trashed-record-to-force-delete',
                ]));

                $record->delete();

                $result = $facade::delete('trashed-record-to-force-delete', true);

                expect($result)->toBeTrue();
                expect($model::withTrashed()->whereKey($record->getKey())->exists())->toBeFalse();
            });

            it('returns false when force deleting a missing record', function () use ($facade) {
                $result = $facade::delete((string) str()->uuid(), true);

                expect($result)->toBeFalse();
            });
        });

        describe('restore', function () use ($facade, $model, $createData) {
            it('restores an existing trashed record by code', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-restore',
                ]));

                $record->delete();

                $result = $facade::restore('record-to-restore');

                expect($result)->toBeTrue();
                expect($model::query()->whereKey($record->getKey())->exists())->toBeTrue();
                expect($model::withTrashed()->whereKey($record->getKey())->first()->trashed())->toBeFalse();
            });

            it('restores records by code instead of database id', function () use ($facade, $model, $createData) {
                $first = $model::factory()->create(array_merge($createData, [
                    'code' => 'first-code',
                ]));

                $second = $model::factory()->create(array_merge($createData, [
                    'code' => (string) $first->getKey(),
                ]));

                $second->delete();

                $result = $facade::restore((string) $first->getKey());

                expect($result)->toBeTrue();
                expect($model::query()->whereKey($first->getKey())->exists())->toBeTrue();
                expect($model::query()->whereKey($second->getKey())->exists())->toBeTrue();
                expect($model::withTrashed()->whereKey($second->getKey())->first()->trashed())->toBeFalse();
            });

            it('returns false when restoring a missing record', function () use ($facade) {
                $result = $facade::restore((string) str()->uuid());

                expect($result)->toBeFalse();
            });

            it('returns false when restoring a record that is not trashed', function () use ($facade, $model, $createData) {
                $model::factory()->create(array_merge($createData, [
                    'code' => 'active-record-to-restore',
                ]));

                $result = $facade::restore('active-record-to-restore');

                expect($result)->toBeFalse();
            });
        });

        describe('trashed', function () use ($facade, $model, $createData) {
            it('returns an empty collection when no records are trashed', function () use ($facade, $model, $createData) {
                $model::factory()->create($createData);

                $result = $facade::trashed();

                expect($result)->toBeInstanceOf(Collection::class);
                expect($result)->toBeEmpty();
            });

            it('returns only trashed records', function () use ($facade, $model, $createData) {
                $active = $model::factory()->create(array_merge($createData, [
                    'code' => 'active-record',
                ]));

                $trashed = $model::factory()->create(array_merge($createData, [
                    'code' => 'trashed-record',
                ]));

                $trashed->delete();

                $result = $facade::trashed();
                $codes = $result->pluck('code')->all();

                expect($result)->toBeInstanceOf(Collection::class);
                expect($result)->toHaveCount(1);
                expect($codes)->toContain($trashed->code);
                expect(in_array($active->code, $codes, true))->toBeFalse();
            });

            it('returns model instances', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create($createData);

                $record->delete();

                $result = $facade::trashed();

                expect($result->first())->toBeInstanceOf($model);
                expect($result->first()->trashed())->toBeTrue();
            });
        });
    });
}
