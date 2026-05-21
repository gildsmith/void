<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Product;

use Gildsmith\Contract\Models\HasCodeInterface;
use Illuminate\Support\Collection;

/**
 * A single allowed value for an Attribute,
 * e.g. "red" for colour or "M" for size.
 *
 * @property-read int $id
 *  Surrogate primary key for database relations.
 *  Used internally for efficient joins and indexing.
 * @property-read string $code
 *  Unique business identifier.
 *  Immutable and used for lookups.
 * @property string $name
 *                        Human-readable name.
 * @property int $attribute_id
 *                             Foreign key for the parent attribute.
 * @property-read \DateTimeInterface|null $deleted_at
 *  Timestamp when the attribute value was soft deleted, or null if active.
 * @property-read AttributeInterface $attribute
 *  The parent Attribute this value belongs to.
 * @property-read Collection<int, ProductInterface> $products
 *  Products that have selected this value
 *  for the corresponding attribute.
 */
interface AttributeValueInterface extends HasCodeInterface {}
