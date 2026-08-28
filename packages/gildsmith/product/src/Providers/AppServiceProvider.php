<?php

declare(strict_types=1);

namespace Gildsmith\Product\Providers;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\Facades\ProductFacadeInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Facades\ProductFacade;
use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\AttributeValue;
use Gildsmith\Product\Models\Product;
use Gildsmith\Product\Models\ProductCollection;
use Gildsmith\Support\Providers\Concerns\BuildsPackagePaths;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    use BuildsPackagePaths;

    public function register(): void
    {
        // Facades
        $this->app->bind(ProductFacadeInterface::class, fn () => new ProductFacade);

        // Models
        $this->app->bind(AttributeValueInterface::class, AttributeValue::class);
        $this->app->bind(AttributeInterface::class, Attribute::class);
        $this->app->bind(ProductInterface::class, Product::class);
        $this->app->bind(ProductCollectionInterface::class, ProductCollection::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom($this->packagePath('database/migrations'));
        $this->loadRoutesFrom($this->packagePath('routes/api.php'));
    }
}
