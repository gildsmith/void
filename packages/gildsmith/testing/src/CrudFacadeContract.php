<?php

declare(strict_types=1);

use Gildsmith\Contract\Facades\CrudFacadeInterface;
use Gildsmith\Contract\Models\HasCodeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @param  class-string<Facade>  $facade
 * @param  class-string<Model>  $model
 * @param  array<string, mixed>  $createData
 * @param  array<string, mixed>  $updateData
 */
function itFulfillsCrudFacadeContract(
    string $facade,
    string $model,
    array $createData = [],
    array $updateData = [],
): void {
    describe($facade, function () use ($facade, $model, $createData, $updateData) {
        $createAttributes = fn (array $overrides = []): array => $model::factory()->raw(array_merge($createData, $overrides));
        $updateAttributes = function (array $overrides = []) use ($model, $updateData): array {
            $attributes = $model::factory()->raw(array_merge($updateData, $overrides));

            unset($attributes['code']);

            return $attributes;
        };
        $attribute = fn (Model $record, string $key, mixed $expected): mixed => is_array($expected) && method_exists($record, 'getTranslations')
            ? $record->getTranslations($key)
            : $record->getAttribute($key);

        describe('configuration', function () use ($facade, $model, $createData) {
            it('receives a valid CRUD facade', function () use ($facade) {
                expect(is_subclass_of($facade, Facade::class))->toBeTrue();
                expect($facade::getFacadeRoot())->toBeInstanceOf(CrudFacadeInterface::class);
            });

            it('receives a valid Eloquent model', function () use ($model) {
                expect(is_subclass_of($model, Model::class))->toBeTrue();
                expect(in_array(HasFactory::class, class_uses_recursive($model), true))->toBeTrue();
                expect(fn () => $model::factory())->not->toThrow(Throwable::class);
                expect($model::factory()->modelName())->toBe($model);
            });

            it('receives a model with a code attribute', function () use ($model, $createData) {
                $record = $model::factory()->create($createData);

                expect($record)->toBeInstanceOf(HasCodeInterface::class);
                expect($record->getAttribute('code'))->toBeString();
                expect($record->getAttribute('code'))->not->toBeEmpty();
                expect($record->getCode())->toBe($record->getAttribute('code'));
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

        describe('create method', function () use ($facade, $model, $createAttributes, $attribute) {
            it('creates a record', function () use ($facade, $model, $createAttributes) {
                $attributes = $createAttributes();

                expect($attributes)->toHaveKey('code');

                $result = $facade::create($attributes);

                expect($result)->toBeInstanceOf($model);
                expect($model::query()->whereKey($result->getKey())->exists())->toBeTrue();
            });

            it('persists provided data', function () use ($facade, $model, $createAttributes, $attribute) {
                $attributes = $createAttributes();

                expect($attributes)->not->toBeEmpty();
                expect($attributes)->toHaveKey('code');

                $result = $facade::create($attributes);
                $record = $model::query()->find($result->getKey());

                foreach ($attributes as $key => $value) {
                    expect($attribute($record, $key, $value))->toBe($value);
                }
            });

            it('fails when code is missing', function () use ($facade, $createAttributes) {
                $attributes = $createAttributes();

                unset($attributes['code']);

                expect(fn () => $facade::create($attributes))->toThrow(Exception::class);
            });
        });

        describe('update method', function () use ($facade, $model, $createData, $updateAttributes, $attribute) {
            it('updates an existing record by code', function () use ($facade, $model, $createData, $updateAttributes, $attribute) {
                $updates = $updateAttributes();

                expect($updates)->not->toBeEmpty();

                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-update',
                ]));

                $result = $facade::update('record-to-update', $updates);

                expect($result)->toBeInstanceOf($model);
                expect($result->getKey())->toBe($record->getKey());

                foreach ($updates as $key => $value) {
                    expect($attribute($result, $key, $value))->toBe($value);
                }

                $persisted = $model::query()->find($record->getKey());

                foreach ($updates as $key => $value) {
                    expect($attribute($persisted, $key, $value))->toBe($value);
                }
            });

            it('does not allow changing the record code', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'original-code',
                ]));

                expect(fn () => $facade::update('original-code', [
                    'code' => 'changed-code',
                ]))->toThrow(Exception::class);

                expect($model::query()->whereKey($record->getKey())->value('code'))->toBe('original-code');
            });

            it('returns null when record does not exist', function () use ($facade, $updateAttributes) {
                $updates = $updateAttributes();

                expect($updates)->not->toBeEmpty();

                expect($facade::update((string) str()->uuid(), $updates))->toBeNull();
            });
        });

        describe('updateOrCreate method', function () use ($facade, $model, $createData, $updateAttributes, $attribute) {
            it('creates a missing record by code', function () use ($facade, $model, $updateAttributes, $attribute) {
                $updates = $updateAttributes();

                expect($updates)->not->toBeEmpty();

                $result = $facade::updateOrCreate('record-to-create', $updates);

                expect($result)->toBeInstanceOf($model);
                expect($result->getAttribute('code'))->toBe('record-to-create');
                expect($model::query()->where('code', 'record-to-create')->exists())->toBeTrue();

                foreach ($updates as $key => $value) {
                    expect($attribute($result, $key, $value))->toBe($value);
                }
            });

            it('updates an existing record by code', function () use ($facade, $model, $createData, $updateAttributes, $attribute) {
                $updates = $updateAttributes();

                expect($updates)->not->toBeEmpty();

                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-upsert',
                ]));

                $result = $facade::updateOrCreate('record-to-upsert', $updates);

                expect($result)->toBeInstanceOf($model);
                expect($result->getKey())->toBe($record->getKey());

                foreach ($updates as $key => $value) {
                    expect($attribute($result, $key, $value))->toBe($value);
                }

                $persisted = $model::query()->find($record->getKey());

                foreach ($updates as $key => $value) {
                    expect($attribute($persisted, $key, $value))->toBe($value);
                }
            });

            it('does not create duplicate records when updating an existing record', function () use ($facade, $model, $createData, $updateAttributes) {
                $updates = $updateAttributes();

                expect($updates)->not->toBeEmpty();

                $model::factory()->create(array_merge($createData, [
                    'code' => 'record-to-upsert',
                ]));

                $facade::updateOrCreate('record-to-upsert', $updates);

                $count = $model::query()->where('code', 'record-to-upsert')->count();

                expect($count)->toBe(1);
            });

            it('does not allow changing the record code through data', function () use ($facade, $model, $createData) {
                $record = $model::factory()->create(array_merge($createData, [
                    'code' => 'original-code',
                ]));

                expect(fn () => $facade::updateOrCreate('original-code', [
                    'code' => 'changed-code',
                ]))->toThrow(Exception::class);

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
