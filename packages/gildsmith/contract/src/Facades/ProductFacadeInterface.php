<?php

namespace Gildsmith\Contract\Facades;

use Gildsmith\Contract\Facades\Product\AttributeFacadeInterface;
use Gildsmith\Contract\Facades\Product\AttributeValueFacadeInterface;
use Gildsmith\Contract\Facades\Product\BlueprintFacadeInterface;
use Gildsmith\Contract\Facades\Product\ProductCollectionFacadeInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Contract\Facades\TrashableFacadeInterface;

/**
 * Product Facade serves as a programmatic interface for managing products.
 *
 * It mimics an internal API, allowing consumers to perform product
 * operations without knowledge of the underlying implementation.
 *
 * @extends TrashableFacadeInterface<ProductInterface>
 */
interface ProductFacadeInterface extends TrashableFacadeInterface
{
    /**
     * Access the attribute management facade.
     */
    public function attribute(): AttributeFacadeInterface;

    /**
     * Access the attribute value management facade.
     */
    public function attributeValue(): AttributeValueFacadeInterface;

    /**
     * Access the blueprint management facade.
     */
    public function blueprint(): BlueprintFacadeInterface;

    /**
     * Access the product collection management facade.
     */
    public function collection(): ProductCollectionFacadeInterface;
}
