<?php

namespace Gildsmith\Contract\Product;

use Illuminate\Support\Collection;

/**
 * Blueprint is a set of validation rules for product fields.
 * You can think of a blueprint as an abstract "chair" or a "skirt" that
 * requires certain attributes to be complete.
 *
 * This object is similar to Attribute Sets in Magento.
 * It defines which attributes are expected on a product,
 * and optionally enforces strict mode, forbidding extra ones.
 *
 * @property-read string $id
 *  Surrogate primary key for database relations.
 *  Used internally for efficient joins and indexing.
 *
 * @property-read string $code
 *  Unique business identifier.
 *  Immutable and used for lookups.
 *
 * @property string $name
 *  Human-readable name.
 *
 * @property Collection<int, AttributeInterface> $attributes
 *  List of all attributes attached to this.
 *  These define the structure expected of a product.
 *
 * @property Collection<int, ProductInterface> $products
 *  Products currently assigned to this blueprint.
 *
 * @property-read bool $strict
 *  Whether this Blueprint forbids any attributes that
 *  are not explicitly declared in $attributes.
 */
interface BlueprintInterface
{
    /**
     * Determines whether the given attribute codes
     * are allowed on products using this blueprint.
     */
    public function allows(string ...$codes): bool;

    /**
     * Checks if the given attribute codes are both
     * allowed and required by this blueprint.
     */
    public function requires(string ...$codes): bool;
}
