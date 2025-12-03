<?php

namespace Gildsmith\Contract\Product;

use Illuminate\Support\Collection;

/**
 * In Gildsmith, a Product represents a variant of a sellable item.
 * Each Product is a standalone entity with its own attributes and metadata,
 * but without pricing or stock information — those are added separately.
 *
 * Whether something is a distinct Product is up to your design, but a good
 * rule of thumb is: if it would have a unique EAN, it probably makes sense
 * as a separate Product instance.
 *
 * Products are structured using Blueprints — predefined patterns that specify
 * which attributes are required for a given type. For example, a chair
 * might require dimensions and colour, while a car might require number of doors.
 *
 * @property-read string $id
 *  Surrogate primary key for database relations.
 *  Used internally for efficient joins and indexing.
 *
 * @property-read string $code
 *  Unique business identifier.
 *  Immutable and used for lookups.
 *
 * @property-read string $name
 *  Human-readable name.
 *
 * @property-read BlueprintInterface $blueprint
 *  A blueprint that defines what attributes should the model have.
 *
 * @property-read Collection<int, ProductCollectionInterface> $collections
 *  A list of all collections that the product is part of.
 *
 * @property-read Collection<int, AttributeValueInterface> $attributeValues
 *  A collection of all attribute values assigned to this product. Since
 *  each value belongs to exactly one attribute, it shouldn't be difficult
 *  to get to an attribute.
 *
 * @property-read \DateTimeInterface|null $created_at
 *  Timestamp when the product was created.
 *
 * @property-read \DateTimeInterface|null $updated_at
 *  Timestamp when the product was last updated.
 *
 * @property-read \DateTimeInterface|null $deleted_at
 *  Timestamp when the product was soft deleted, or null if active.
 */
interface ProductInterface {}
