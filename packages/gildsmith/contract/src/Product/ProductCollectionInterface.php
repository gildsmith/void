<?php

namespace Gildsmith\Contract\Product;

use Illuminate\Support\Collection;

/**
 * Product Collection is an umbrella term to describe every kind of products set.
 * Instead of having bundles, variants, categories, and more, we have this object.
 *
 * Collections are purpose-agnostic. It means that a collection can mean many
 * things and be used for different reasons. You can even have a collection that's
 * completely virtual and not visible to users.
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
 * @property-read Collection<int, ProductInterface> $products
 * Products included in this collection.
 *
 * @property string $type
 *  Describes the purpose or usage of this collection.
 */
interface ProductCollectionInterface {}
