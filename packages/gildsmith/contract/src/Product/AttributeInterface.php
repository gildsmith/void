<?php

namespace Gildsmith\Contract\Product;

use Illuminate\Support\Collection;

/**
 * Represents a product attribute (e.g., colour, size, material).
 * Defines a category of characteristics that products can possess,
 * each with a predefined set of possible values.
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
 * @property-read Collection<int, AttributeValueInterface> $values
 *  Collection of all values available for this attribute.
 *  Each entry is an AttributeValue representing one possible option.
 *
 * @property-read Collection<int, BlueprintInterface> $blueprints
 *  Blueprints that include this attribute in their definition.
 */
interface AttributeInterface {}
