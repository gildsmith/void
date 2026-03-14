<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Exceptions\MissingSoftDeletesException;
use Gildsmith\Product\Facades\ProductFacade as ProductFacadeConcrete;
use Gildsmith\Product\Models\Product;
use Gildsmith\Support\Facades\Product as ProductFacade;
use Illuminate\Database\Eloquent\Model;

covers(ProductFacadeConcrete::class);

describe('all method', function () {

    it('returns empty when only trashed products exist and flag is false', function () {
        Product::factory()->trashed()->count(3)->create();

        $result = ProductFacade::all();
        expect($result)->toBeEmpty();
    });

    it('returns empty when no products exist in the database', function () {
        $result = ProductFacade::all();
        expect($result)->toBeEmpty();
    });

    it('returns only active products by default', function () {
        Product::factory()->count(1)->create();
        Product::factory()->trashed()->count(2)->create();

        $result = ProductFacade::all();
        expect($result)->toHaveCount(1);
    });

    it('includes trashed products when flag is true', function () {
        Product::factory()->count(1)->create();
        Product::factory()->trashed()->count(2)->create();

        $result = ProductFacade::all(withTrashed: true);
        expect($result)->toHaveCount(3);
    });

    it('throws exception if model lacks SoftDeletes and flag is true', function () {
        $model = new class extends Model { protected $table = 'products'; };
        bind(ProductInterface::class, $model::class);

        ProductFacade::all(withTrashed: true);
    })->throws(MissingSoftDeletesException::class);

    it('silently ignores missing SoftDeletes trait if flag is false', function () {
        $model = new class extends Model { protected $table = 'products'; };
        bind(ProductInterface::class, $model::class);

        expect(fn () => ProductFacade::all())->not->toThrow(MissingSoftDeletesException::class);
    });
});
