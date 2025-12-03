<?php

declare(strict_types=1);

namespace Gildsmith\Product\Providers;

use Gildsmith\Contract\Facades\Product\AttributeFacadeInterface;
use Gildsmith\Contract\Facades\Product\AttributeValueFacadeInterface;
use Gildsmith\Contract\Facades\Product\BlueprintFacadeInterface;
use Gildsmith\Contract\Facades\Product\ProductCollectionFacadeInterface;
use Gildsmith\Contract\Facades\ProductFacadeInterface;
use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Facades\AttributeFacade;
use Gildsmith\Product\Facades\AttributeValueFacade;
use Gildsmith\Product\Facades\BlueprintFacade;
use Gildsmith\Product\Facades\ProductCollectionFacade;
use Gildsmith\Product\Facades\ProductFacade;
use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\AttributeValue;
use Gildsmith\Product\Models\Blueprint;
use Gildsmith\Product\Models\Product;
use Gildsmith\Product\Models\ProductCollection;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Facades
        $this->app->bind(ProductFacadeInterface::class, fn () => new ProductFacade);
        $this->app->bind(AttributeFacadeInterface::class, fn () => new AttributeFacade);
        $this->app->bind(AttributeValueFacadeInterface::class, fn () => new AttributeValueFacade);
        $this->app->bind(BlueprintFacadeInterface::class, fn () => new BlueprintFacade);
        $this->app->bind(ProductCollectionFacadeInterface::class, fn () => new ProductCollectionFacade);

        // Models
        $this->app->bind(AttributeValueInterface::class, AttributeValue::class);
        $this->app->bind(AttributeInterface::class, Attribute::class);
        $this->app->bind(BlueprintInterface::class, Blueprint::class);
        $this->app->bind(ProductInterface::class, Product::class);
        $this->app->bind(ProductCollectionInterface::class, ProductCollection::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom($this->packagePath('database/migrations'));
        $this->loadRoutesFrom($this->packagePath('routes/api.php'));
    }

    /**
     * Helper function to build paths from the package root.
     */
    private function packagePath(string $path): string
    {
        return dirname(__DIR__, 2).'/'.$path;
    }
}
