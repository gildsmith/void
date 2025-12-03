<?php

declare(strict_types=1);

use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Exceptions\MissingSoftDeletesException;
use Gildsmith\Product\Facades\ProductFacade as ProductFacadeConcrete;
use Gildsmith\Product\Models\Blueprint;
use Gildsmith\Product\Models\Product as ProductModel;
use Gildsmith\Support\Facades\Product as ProductFacade;
use Illuminate\Database\Eloquent\Model;

covers(ProductFacadeConcrete::class);

describe('all method', function () {
    it('returns all products', function () {
        ProductModel::factory()->count(3)->create();

        $products = ProductFacade::all();

        expect($products)->toHaveCount(3);
    });

    it('returns trashed products when second parameter is true', function () {
        ProductModel::factory()->count(2)->create();
        ProductModel::factory()->trashed()->count(1)->create();

        $products = ProductFacade::all(true);

        expect($products)->toHaveCount(3);
    });

    it('throws an exception if SoftDeletes is not used by a model', function () {
        bind(ProductInterface::class, function () {
            return new class extends Model implements ProductInterface {};
        });

        ProductFacade::all(true);
    })->throws(MissingSoftDeletesException::class);
});

describe('create method', function () {
    it('creates a product via the facade', function () {
        $mockProduct = Mockery::mock(ProductInterface::class);
        $mockProduct->allows('create')->once()->andReturns($mockProduct);

        bind(ProductInterface::class, fn () => $mockProduct);

        ProductFacade::create([]);
    });
});

describe('delete method', function () {
    it('soft deletes a product', function () {
        $product = ProductModel::factory()->createOne();

        $result = ProductFacade::delete($product->code);

        expect($result)->toBeTrue();
        expect(ProductModel::withTrashed()->find($product->id)->trashed())->toBeTrue();
    });

    it('force deletes a product when second parameter is true', function () {
        $product = ProductModel::factory()->createOne();

        $result = ProductFacade::delete($product->code, true);

        expect($result)->toBeTrue();
        expect(ProductModel::withTrashed()->find($product->id))->toBeNull();
    });

    it('throws an exception if SoftDeletes is not used by a model', function () {
        $product = new class extends Model implements ProductInterface {};

        ProductFacade::partialMock()
            ->expects('find')
            ->andReturn($product)
            ->once();

        ProductFacade::delete('code', true);
    })->throws(MissingSoftDeletesException::class);
});

describe('find method', function () {
    it('returns a product by code', function () {
        $product = ProductModel::factory()->createOne();

        $found = ProductFacade::find($product->code);

        expect($found)->toBeInstanceOf(ProductModel::class);
        expect($found->code)->toBe($product->code);
    });

    it('returns trashed product when second parameter is true', function () {
        $product = ProductModel::factory()->createOne();
        $product->delete();

        $found = ProductFacade::find($product->code, true);

        expect($found)->not->toBeNull();
        expect($found->trashed())->toBeTrue();
    });

    it('throws an exception if SoftDeletes is not used by a model', function () {
        bind(ProductInterface::class, function () {
            return new class extends Model implements ProductInterface {};
        });

        ProductFacade::find('code', true);
    })->throws(MissingSoftDeletesException::class);
});

describe('restore method', function () {
    it('restores a trashed product', function () {
        $product = ProductModel::factory()->createOne();
        $product->delete();

        $result = ProductFacade::restore($product->code);

        expect($result)->toBeTrue();
        expect(ProductModel::find($product->id)->trashed())->toBeFalse();
    });

    it('throws an exception if SoftDeletes is not used by a model', function () {
        bind(ProductInterface::class, function () {
            return new class extends Model implements ProductInterface {};
        });

        ProductFacade::restore('code');
    })->throws(MissingSoftDeletesException::class);
});

describe('trashed method', function () {
    it('returns only trashed products', function () {
        ProductModel::factory()->count(2)->create();
        ProductModel::factory()->trashed()->count(1)->create();

        $products = ProductFacade::trashed();

        expect($products->count())->toBe(1);
    });

    it('throws an exception if SoftDeletes is not used by a model', function () {
        bind(ProductInterface::class, function () {
            return new class implements ProductInterface {};
        });

        ProductFacade::trashed();
    })->throws(MissingSoftDeletesException::class);
});

describe('update method', function () {
    it('updates a product and returns a fresh instance', function () {
        $product = ProductModel::factory()->createOne();

        ProductFacade::partialMock()
            ->expects('find')
            ->andReturn($product)
            ->once();

        ProductFacade::update('code', []);
    });
});

describe('updateOrCreate method', function () {
    it('updates an existing product', function () {
        $product = ProductModel::factory()->createOne([
            'name' => ['en' => 'Old', 'pl' => 'Old'],
        ]);

        $updated = ProductFacade::updateOrCreate($product->code, [
            'name' => ['en' => 'New', 'pl' => 'New'],
        ]);

        expect($updated->getTranslations('name')['en'])->toBe('New');
    });

    it('creates a new product when it does not exist', function () {
        $blueprint = Blueprint::factory()->createOne();
        $attributes = ProductModel::factory()->makeOne([
            'blueprint_id' => $blueprint->id,
        ]);

        $created = Model::unguarded(fn () => ProductFacade::updateOrCreate($attributes->code, [
            'blueprint_id' => $blueprint->id,
            'name' => $attributes->getTranslations('name'),
        ]));

        expect($created->code)->toBe($attributes->code);
        expect(ProductModel::count())->toBe(1);
    });
});
