<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Routing\ResourceAbility;
use Illuminate\Routing\Route as LaravelRoute;

it('registers GET blueprints/{code}/attributes', function () {
    $route = gildsmithTestingFindRoute('GET', 'blueprints/{code}/attributes');

    expect($route)->toBeInstanceOf(LaravelRoute::class);
    expect($route?->gatherMiddleware())->toContain('can:'.ResourceAbility::View->value.','.BlueprintInterface::class);
});

it('registers POST blueprints/{code}/attributes/{attribute}', function () {
    $route = gildsmithTestingFindRoute('POST', 'blueprints/{code}/attributes/{attribute}');

    expect($route)->toBeInstanceOf(LaravelRoute::class);
    expect($route?->gatherMiddleware())->toContain('can:'.ResourceAbility::Update->value.','.BlueprintInterface::class);
});

it('registers PATCH blueprints/{code}/attributes/{attribute}', function () {
    $route = gildsmithTestingFindRoute('PATCH', 'blueprints/{code}/attributes/{attribute}');

    expect($route)->toBeInstanceOf(LaravelRoute::class);
    expect($route?->gatherMiddleware())->toContain('can:'.ResourceAbility::Update->value.','.BlueprintInterface::class);
});

it('registers DELETE blueprints/{code}/attributes/{attribute}', function () {
    $route = gildsmithTestingFindRoute('DELETE', 'blueprints/{code}/attributes/{attribute}');

    expect($route)->toBeInstanceOf(LaravelRoute::class);
    expect($route?->gatherMiddleware())->toContain('can:'.ResourceAbility::Update->value.','.BlueprintInterface::class);
});
