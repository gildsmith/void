<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Contract\Routing\ResourceAbility;
use Gildsmith\Product\Policies\AttributePolicy;
use Gildsmith\Product\Policies\AttributeValuePolicy;
use Gildsmith\Product\Policies\BlueprintPolicy;
use Gildsmith\Product\Policies\ProductCollectionPolicy;
use Gildsmith\Product\Policies\ProductPolicy;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Gate;

it('registers default product package policies', function () {
    expect(Gate::getPolicyFor(AttributeInterface::class))->toBeInstanceOf(AttributePolicy::class);
    expect(Gate::getPolicyFor(AttributeValueInterface::class))->toBeInstanceOf(AttributeValuePolicy::class);
    expect(Gate::getPolicyFor(BlueprintInterface::class))->toBeInstanceOf(BlueprintPolicy::class);
    expect(Gate::getPolicyFor(ProductCollectionInterface::class))->toBeInstanceOf(ProductCollectionPolicy::class);
    expect(Gate::getPolicyFor(ProductInterface::class))->toBeInstanceOf(ProductPolicy::class);
});

it('denies product package access by default', function () {
    $user = new GenericUser(['id' => 1]);

    expect(Gate::forUser($user)->allows(ResourceAbility::Create->value, ProductInterface::class))->toBeFalse();
});
