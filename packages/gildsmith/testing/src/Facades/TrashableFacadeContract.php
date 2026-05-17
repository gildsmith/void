<?php

declare(strict_types=1);

use Gildsmith\Contract\Facades\TrashableFacadeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @param  class-string  $facade
 * @param  class-string  $model
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

            it('receives a soft deletable Eloquent model', function () use ($model) {
                expect(is_subclass_of($model, Model::class))->toBeTrue();
                expect(in_array(SoftDeletes::class, class_uses_recursive($model), true))->toBeTrue();
            });
        });

        describe('all method with trashed records', function () use ($facade, $model, $createData) {
            it('excludes trashed records by default', function () use ($facade, $model, $createData) {
                $active = $model::factory()->create(array_merge($createData, [
                    'code' => 'active-record',
                ]));
                $model::factory()->trashed()->create(array_merge($createData, [
                    'code' => 'trashed-record',
                ]));

                $result = $facade::all();

                expect($result)->toBeInstanceOf(Collection::class);
                expect($result->pluck('code')->all())->toBe([$active->code]);
            });

            it('includes trashed records when requested', function () use ($facade, $model, $createData) {
                $active = $model::factory()->create(array_merge($createData, [
                    'code' => 'active-record',
                ]));
                $trashed = $model::factory()->trashed()->create(array_merge($createData, [
                    'code' => 'trashed-record',
                ]));

                $result = $facade::all(true);

                expect($result)->toHaveCount(2);
                expect($result->pluck('code')->all())->toContain($active->code, $trashed->code);
            });
        });

        describe('find method with trashed records', function () use ($facade, $model, $createData) {
            it('excludes trashed records by default', function () use ($facade, $model, $createData) {
                $model::factory()->trashed()->create(array_merge($createData, [
                    'code' => 'trashed-record',
                ]));

                expect($facade::find('trashed-record'))->toBeNull();
            });

            it('finds trashed records when requested', function () use ($facade, $model, $createData) {
                $record = $model::factory()->trashed()->create(array_merge($createData, [
                    'code' => 'trashed-record',
                ]));

                $result = $facade::find('trashed-record', true);

                expect($result)->toBeInstanceOf($model);
                expect($result->getKey())->toBe($record->getKey());
            });
        });

        describe('delete method with trashed records', function () use ($facade, $model, $createData) {
            it('soft deletes by default', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-trash',
                ]));

                $result = $facade::delete('record-to-trash');

                expect($result)->toBeTrue();
                expect($model::query()->whereKey($record->getKey())->exists())->toBeFalse();
                expect($model::withTrashed()->whereKey($record->getKey())->exists())->toBeTrue();
            });

            it('force deletes trashed records', function () use ($facade, $model, $createData) {
                $record = $model::factory()->trashed()->create(array_merge($createData, [
                    'code' => 'record-to-force-delete',
                ]));

                $result = $facade::delete('record-to-force-delete', true);

                expect($result)->toBeTrue();
                expect($model::withTrashed()->whereKey($record->getKey())->exists())->toBeFalse();
            });
        });

        describe('restore method', function () use ($facade, $model, $createData) {
            it('restores a trashed record by code', function () use ($facade, $model, $createData) {
                $record = $model::factory()->trashed()->create(array_merge($createData, [
                    'code' => 'record-to-restore',
                ]));

                $result = $facade::restore('record-to-restore');

                expect($result)->toBeTrue();
                expect($model::query()->whereKey($record->getKey())->exists())->toBeTrue();
            });

            it('returns false when record does not exist', function () use ($facade) {
                expect($facade::restore((string) str()->uuid()))->toBeFalse();
            });
        });

        describe('trashed method', function () use ($facade, $model, $createData) {
            it('returns only trashed records', function () use ($facade, $model, $createData) {
                $model::factory()->create(array_merge($createData, [
                    'code' => 'active-record',
                ]));
                $trashed = $model::factory()->trashed()->create(array_merge($createData, [
                    'code' => 'trashed-record',
                ]));

                $result = $facade::trashed();

                expect($result)->toBeInstanceOf(Collection::class);
                expect($result)->toHaveCount(1);
                expect($result->first()->getKey())->toBe($trashed->getKey());
            });
        });
    });
}
