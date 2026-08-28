<?php

declare(strict_types=1);

namespace Gildsmith\Contract\Product;

use DateTimeInterface;
use Gildsmith\Contract\Shared\HasCodeInterface;
use Illuminate\Support\Collection;

/**
 * Product Collection is an umbrella term to describe every kind of products set.
 * Instead of having bundles, variants, categories, and more, we have this object.
 *
 * Collections are purpose-agnostic. It means that a collection can mean many
 * things and be used for different reasons. You can even have a collection that's
 * completely virtual and not visible to users.
 *
 * @property-read int $id
 *  Surrogate primary key for database relations.
 *  Used internally for efficient joins and indexing.
 * @property string $name
 *  Human-readable name.
 * @property string $type
 *  Describes the purpose or usage of this collection.
 * @property-read DateTimeInterface|null $created_at
 *  Timestamp when the collection was created.
 * @property-read DateTimeInterface|null $updated_at
 *  Timestamp when the collection was last updated.
 * @property-read DateTimeInterface|null $deleted_at
 *  Timestamp when the collection was soft deleted, or null if active.
 * @property-read Collection<int, ProductInterface> $products
 *  Products included in this collection.
 */
interface ProductCollectionInterface extends HasCodeInterface {}
