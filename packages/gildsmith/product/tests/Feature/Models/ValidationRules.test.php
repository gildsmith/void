<?php

declare(strict_types=1);

use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\ProductCollection;
use Gildsmith\Support\Utils\ValidationRules;
use Illuminate\Validation\ValidationException;

it('adds default code validation rules to models using has code', function (): void {
    $rules = (new Attribute)->getValidationRules();

    expect($rules)
        ->toHaveKey('code')
        ->and($rules['code'])
        ->toBe(explode('|', ValidationRules::CODE));
});

it('requires code for create but not update on models using has code', function (): void {
    $attribute = new Attribute;

    expect($attribute->getCreateValidationRules()['code'])
        ->toBe(['required', ...explode('|', ValidationRules::CODE)])
        ->and($attribute->getUpdateValidationRules()['code'])
        ->toBe(explode('|', ValidationRules::CODE));
});

it('keeps explicit model validation rules alongside default code rules', function (): void {
    $rules = (new ProductCollection)->getValidationRules();

    expect($rules)
        ->toHaveKey('code')
        ->and($rules)
        ->toHaveKey('type')
        ->and($rules['code'])
        ->toBe(explode('|', ValidationRules::CODE))
        ->and($rules['type'])
        ->toBe(explode('|', ValidationRules::CODE));
});

it('validates models when they are created', function (): void {
    expect(fn () => Attribute::query()->create([
        'code' => 'Invalid Code',
        'name' => ['en' => 'Colour'],
    ]))->toThrow(ValidationException::class);
});

it('validates models when they are updated', function (): void {
    $collection = ProductCollection::factory()->create();

    $collection->type = 'Invalid Type';

    expect(fn () => $collection->save())->toThrow(ValidationException::class);
});
