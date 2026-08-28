<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models;

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Database\Factories\ProductFactory;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Model\Concerns\HasCode;
use Gildsmith\Support\Model\Concerns\HasImmutableAttributes;
use Gildsmith\Support\Model\Concerns\HasValidationRules;
use Gildsmith\Support\Model\Contracts\HasValidationRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements HasValidationRulesInterface, ProductInterface
{
    use HasAbstractRelationships;
    use HasCode;
    use HasFactory;
    use HasImmutableAttributes;
    use HasTranslations;
    use HasValidationRules;
    use SoftDeletes;

    protected array $translatable = ['name'];

    protected array $immutable = ['code'];

    protected $fillable = ['code', 'name'];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(ProductCollectionInterface::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValueInterface::class);
    }
}
