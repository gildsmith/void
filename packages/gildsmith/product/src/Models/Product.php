<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models;

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Database\Factories\ProductFactory;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Model\Concerns\HasCode;
use Gildsmith\Support\Model\Concerns\HasImmutableAttributes;
use Gildsmith\Support\Model\Concerns\HasValidationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements ProductInterface
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

    protected $fillable = ['code', 'name', 'blueprint_id'];

    protected $casts = [
        'is_complete' => 'bool',
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }

    protected static function booted(): void
    {
        static::created(fn (Product $product) => $product->recalculateCompleteness());
    }

    public function blueprint(): BelongsTo
    {
        return $this->belongsTo(BlueprintInterface::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(ProductCollectionInterface::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValueInterface::class);
    }

    public function markComplete(): bool
    {
        return $this->forceFill(['is_complete' => true])->saveQuietly();
    }

    public function markIncomplete(): bool
    {
        return $this->forceFill(['is_complete' => false])->saveQuietly();
    }

    public function recalculateCompleteness(): bool
    {
        $requiredAttributeIds = $this->blueprint()
            ->first()
            ?->attributes()
            ->wherePivot('required', true)
            ->pluck('attributes.id')
            ->all() ?? [];

        $isComplete = $requiredAttributeIds === []
            || $this->attributeValues()
                ->whereIn('attribute_values.attribute_id', $requiredAttributeIds)
                ->distinct()
                ->count('attribute_values.attribute_id') === count($requiredAttributeIds);

        $this->forceFill(['is_complete' => $isComplete])->saveQuietly();

        return $isComplete;
    }
}
