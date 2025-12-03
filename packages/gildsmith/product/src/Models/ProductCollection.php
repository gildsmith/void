<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models;

use Gildsmith\Contract\Product\ProductCollectionInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Database\Factories\ProductCollectionFactory;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Utils\ValidationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ProductCollection extends Model implements ProductCollectionInterface
{
    use HasAbstractRelationships;
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    protected array $translatable = ['name'];

    protected $fillable = ['code', 'name', 'type'];

    public array $rules = [
        'code' => ValidationRules::CODE,
        'type' => ValidationRules::CODE,
    ];

    protected static function newFactory(): ProductCollectionFactory
    {
        return ProductCollectionFactory::new();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductInterface::class);
    }
}
