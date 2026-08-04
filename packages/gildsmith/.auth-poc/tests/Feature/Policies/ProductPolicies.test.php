<?php

declare(strict_types=1);

use Gildsmith\Auth\Models\User;
use Gildsmith\Auth\Policies\Product\AttributePolicy;
use Gildsmith\Auth\Policies\Product\AttributeValuePolicy;
use Gildsmith\Auth\Policies\Product\BlueprintPolicy;
use Gildsmith\Auth\Policies\Product\ProductCollectionPolicy;
use Gildsmith\Auth\Policies\Product\ProductPolicy;
use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Contract\Routing\ResourceAbility;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Gate;

it('registers default product package policies', function () {
    expect(Gate::getPolicyFor(AttributeInterface::class))->toBeInstanceOf(AttributePolicy::class);
    expect(Gate::getPolicyFor(AttributeValueInterface::class))->toBeInstanceOf(AttributeValuePolicy::class);
    expect(Gate::getPolicyFor(BlueprintInterface::class))->toBeInstanceOf(BlueprintPolicy::class);
    expect(Gate::getPolicyFor(ProductCollectionInterface::class))->toBeInstanceOf(ProductCollectionPolicy::class);
    expect(Gate::getPolicyFor(ProductInterface::class))->toBeInstanceOf(ProductPolicy::class);
});

it('allows product package access for employees only', function () {
    $facade = resolve(UserFacadeInterface::class);
    $customer = User::factory()->create();
    $employee = User::factory()->create();

    $facade->grantEmployeeAccess($employee);

    foreach (ResourceAbility::cases() as $ability) {
        expect(Gate::forUser($customer)->allows($ability->value, ProductInterface::class))->toBeFalse();
        expect(Gate::forUser($employee->refresh())->allows($ability->value, ProductInterface::class))->toBeTrue();
    }

    $facade->revokeEmployeeAccess($employee);

    expect(Gate::forUser($employee->refresh())->allows(ResourceAbility::Create->value, ProductInterface::class))->toBeFalse();
});

it('denies product package access for non-gildsmith users', function () {
    $user = new GenericUser(['id' => 1]);

    expect(Gate::forUser($user)->allows(ResourceAbility::Create->value, ProductInterface::class))->toBeFalse();
});
