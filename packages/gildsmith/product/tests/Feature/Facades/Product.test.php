<?php

declare(strict_types=1);

use Gildsmith\Product\Exceptions\MissingSoftDeletesException;
use Gildsmith\Product\Facades\ProductFacade as ProductFacadeConcrete;

covers(ProductFacadeConcrete::class);

describe('all method', function () {

    it('returns empty when only trashed products exist and flag is false', function () {
        //
    });

    it('returns empty when no products exist in the database', function () {
        //
    });

    it('returns only active products by default', function () {
        //
    });

    it('includes trashed products when flag is true', function () {
        //
    });

    // [case 5] throws an exception if [1] $withTrashed = true and [2] model doesn't use SoftDeletes.
    it('throws exception if model lacks SoftDeletes and flag is true', function () {
        //
    })->throws(MissingSoftDeletesException::class);

    // [case 6] does not throw an exception if [1] $withTrashed = false and [2] model doesn't use SoftDeletes.
    it('silently ignores missing SoftDeletes trait if flag is false', function () {
        //
    });
});
