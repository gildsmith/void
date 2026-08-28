<?php

declare(strict_types=1);

use Gildsmith\Contract\Shared\Routing\ResourceAbility;
use Illuminate\Routing\Route as LaravelRoute;
use Illuminate\Support\Facades\Route;

/**
 * @return array<int, array{method: string, uri: string, ability: ResourceAbility}>
 */
function gildsmithTrashableResourceRouteAuthorizationContract(string $parameter = 'code'): array
{
    $parameter = trim($parameter, '{}');
    $parameter = "{{$parameter}}";

    return [
        ['method' => 'GET', 'ability' => ResourceAbility::ViewAny],
        ['method' => 'POST', 'ability' => ResourceAbility::Create],
        ['method' => 'GET', 'uri' => 'trashed', 'ability' => ResourceAbility::ViewTrashed],
        ['method' => 'POST', 'uri' => "$parameter/trash", 'ability' => ResourceAbility::Trash],
        ['method' => 'POST', 'uri' => "$parameter/restore", 'ability' => ResourceAbility::Restore],
        ['method' => 'GET', 'uri' => $parameter, 'ability' => ResourceAbility::View],
        ['method' => 'PUT', 'uri' => $parameter, 'ability' => ResourceAbility::Update],
        ['method' => 'PATCH', 'uri' => $parameter, 'ability' => ResourceAbility::Update],
        ['method' => 'DELETE', 'uri' => $parameter, 'ability' => ResourceAbility::Delete],
    ];
}

/**
 * @param  class-string  $contract
 */
function itExposesTrashableResourceRoutes(
    string $uri,
    string $contract,
    string $parameter = 'code',
): void {
    $uri = trim($uri, '/');

    describe("$uri routes", function () use ($uri, $contract, $parameter) {
        foreach (gildsmithTrashableResourceRouteAuthorizationContract($parameter) as $routeContract) {
            $method = $routeContract['method'];
            $routeUri = gildsmithTestingRouteUri($uri, $routeContract['uri'] ?? '');
            $ability = $routeContract['ability'];

            it("registers $method $routeUri", function () use ($method, $routeUri, $ability, $contract) {
                $route = gildsmithTestingFindRoute($method, $routeUri);

                expect($route)->toBeInstanceOf(LaravelRoute::class);
                expect($route?->gatherMiddleware())->toContain("can:$ability->value,$contract");
            });
        }
    });
}

function gildsmithTestingRouteUri(string $baseUri, string $uri): string
{
    return trim("$baseUri/$uri", '/');
}

function gildsmithTestingFindRoute(string $method, string $uri): ?LaravelRoute
{
    $uri = trim($uri, '/');

    foreach (Route::getRoutes() as $route) {
        if (! in_array($method, $route->methods(), true)) {
            continue;
        }

        if (trim($route->uri(), '/') === $uri) {
            return $route;
        }
    }

    return null;
}
