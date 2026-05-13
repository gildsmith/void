<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Gildsmith\Contract\Facades\CrudFacadeInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @param class-string<Facade> $facade
 * @param class-string<Model> $model
 * @param array<string, mixed> $createData
 * @param array<string, mixed> $updateData
 */
function itFulfillsCrudFacadeContract(
    string $facade,
    string $model,
    array $createData = [],
    array $updateData = [],
): void {
    describe($facade, function () use ($facade, $model, $createData, $updateData) {
        describe('configuration', function () use ($facade, $model, $createData) {
            it('receives a valid CRUD facade', function () use ($facade) {
                expect(is_subclass_of($facade, Facade::class))->toBeTrue();
                expect($facade::getFacadeRoot())->toBeInstanceOf(CrudFacadeInterface::class);
            });

            it('receives a valid Eloquent model', function () use ($model) {
                expect(is_subclass_of($model, Model::class))->toBeTrue();
                expect(in_array(HasFactory::class, class_uses_recursive($model), true))->toBeTrue();
                expect(fn() => $model::factory())->not->toThrow(Throwable::class);
                expect($model::factory()->modelName())->toBe($model);
            });

            it('receives a model with a code attribute', function () use ($model, $createData) {
                $record = $model::factory()->create($createData);

                expect($record->getAttribute('code'))->toBeString();
                expect($record->getAttribute('code'))->not->toBeEmpty();
            });
        });

        describe('all method', function () use ($facade, $model, $createData) {
            it('returns an empty collection when no records exist', function () use ($facade) {
                $result = $facade::all();

                expect($result)->toBeInstanceOf(Collection::class);
                expect($result)->toBeEmpty();
            });

            it('returns all records', function () use ($facade, $model, $createData) {
                $first = $model::factory()->create($createData);
                $second = $model::factory()->create($createData);

                $result = $facade::all();
                $codes = $result->pluck('code')->all();

                expect($result)->toHaveCount(2);
                expect($codes)->toContain($first->code);
                expect($codes)->toContain($second->code);
            });

            it('returns model instances', function () use ($facade, $model, $createData) {
                $model::factory()->create($createData);

                $result = $facade::all();

                expect($result->first())->toBeInstanceOf($model);
            });
        });

        describe('find method', function () use ($facade, $model, $createData) {
            it('finds an existing record by code', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-find',
                ]));

                $result = $facade::find('record-to-find');

                expect($result)->toBeInstanceOf($model);
                expect($result->getKey())->toBe($record->getKey());
            });

            it('returns null when record does not exist', function () use ($facade) {
                $result = $facade::find((string) str()->uuid());

                expect($result)->toBeNull();
            });

            it('finds records by code instead of database id', function () use ($facade, $model, $createData) {
                $first = $model::factory()->create(array_merge($createData, [
                    'code' => 'first-code',
                ]));

                $second = $model::factory()->create(array_merge($createData, [
                    'code' => (string) $first->getKey(),
                ]));

                $result = $facade::find((string) $first->getKey());

                expect($result)->toBeInstanceOf($model);
                expect($result->getKey())->toBe($second->getKey());
            });
        });

        describe('create method', function () use ($facade, $model, $createData) {
            it('creates a record', function () use ($facade, $model, $createData) {
                expect($createData)->toHaveKey('code');

                $result = $facade::create($createData);

                expect($result)->toBeInstanceOf($model);
                expect($model::query()->whereKey($result->getKey())->exists())->toBeTrue();
            });

            it('persists provided data', function () use ($facade, $model, $createData) {
                expect($createData)->not->toBeEmpty();
                expect($createData)->toHaveKey('code');

                $result = $facade::create($createData);
                $record = $model::query()->find($result->getKey());

                foreach ($createData as $key => $value) {
                    expect($record->getAttribute($key))->toBe($value);
                }
            });

            it('fails when code is missing', function () use ($facade, $createData) {
                $data = $createData;

                unset($data['code']);

                expect(fn() => $facade::create($data))->toThrow(Throwable::class);
            });
        });

        describe('update method', function () use ($facade, $model, $createData, $updateData) {
            it('updates an existing record by code', function () use ($facade, $model, $createData, $updateData) {
                expect($updateData)->not->toBeEmpty();

                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-update',
                ]));

                $result = $facade::update('record-to-update', $updateData);

                expect($result)->toBeInstanceOf($model);
                expect($result->getKey())->toBe($record->getKey());

                foreach ($updateData as $key => $value) {
                    expect($result->getAttribute($key))->toBe($value);
                }

                foreach ($updateData as $key => $value) {
                    expect($model::query()->whereKey($record->getKey())->value($key))->toBe($value);
                }
            });

            it('does not allow changing the record code', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'original-code',
                ]));

                expect(fn() => $facade::update('original-code', [
                    'code' => 'changed-code',
                ]))->toThrow(Throwable::class);

                expect($model::query()->whereKey($record->getKey())->value('code'))->toBe('original-code');
            });

            it('throws when record does not exist', function () use ($facade, $updateData) {
                expect($updateData)->not->toBeEmpty();

                expect(fn() => $facade::update((string) str()->uuid(), $updateData))->toThrow(Throwable::class);
            });
        });

        describe('updateOrCreate method', function () use ($facade, $model, $createData, $updateData) {
            it('creates a missing record by code', function () use ($facade, $model, $updateData) {
                expect($updateData)->not->toBeEmpty();

                $result = $facade::updateOrCreate('record-to-create', $updateData);

                expect($result)->toBeInstanceOf($model);
                expect($result->getAttribute('code'))->toBe('record-to-create');
                expect($model::query()->where('code', 'record-to-create')->exists())->toBeTrue();

                foreach ($updateData as $key => $value) {
                    expect($result->getAttribute($key))->toBe($value);
                }
            });

            it('updates an existing record by code', function () use ($facade, $model, $createData, $updateData) {
                expect($updateData)->not->toBeEmpty();

                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-upsert',
                ]));

                $result = $facade::updateOrCreate('record-to-upsert', $updateData);

                expect($result)->toBeInstanceOf($model);
                expect($result->getKey())->toBe($record->getKey());

                foreach ($updateData as $key => $value) {
                    expect($result->getAttribute($key))->toBe($value);
                }

                foreach ($updateData as $key => $value) {
                    expect($model::query()->whereKey($record->getKey())->value($key))->toBe($value);
                }
            });

            it('does not create duplicate records when updating an existing record', function () use ($facade, $model, $createData, $updateData) {
                expect($updateData)->not->toBeEmpty();

                $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-upsert',
                ]));

                $facade::updateOrCreate('record-to-upsert', $updateData);

                $count = $model::query()->where('code', 'record-to-upsert')->count();

                expect($count)->toBe(1);
            });

            it('does not allow changing the record code through data', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'original-code',
                ]));

                expect(fn() => $facade::updateOrCreate('original-code', [
                    'code' => 'changed-code',
                ]))->toThrow(Throwable::class);

                expect($model::query()->whereKey($record->getKey())->value('code'))->toBe('original-code');
            });
        });

        describe('delete method', function () use ($facade, $model, $createData) {
            it('deletes an existing record by code', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-delete',
                ]));

                $result = $facade::delete('record-to-delete');

                expect($result)->toBeTrue();
                expect($model::query()->whereKey($record->getKey())->exists())->toBeFalse();
            });

            it('returns false when record does not exist', function () use ($facade) {
                $result = $facade::delete((string) str()->uuid());

                expect($result)->toBeFalse();
            });
        });
    });
}
