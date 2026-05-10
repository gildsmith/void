<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models;

use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Database\Factories\ProductFactory;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Utils\ValidationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Product extends Model implements ProductInterface
{
    use HasAbstractRelationships;
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    protected array $translatable = ['name'];

    protected $fillable = ['code', 'name', 'blueprint_id'];

    public array $rules = [
        'code' => ValidationRules::CODE,
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
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
}
