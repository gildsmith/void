<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Database\Factories\AttributeValueFactory;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Utils\ValidationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class AttributeValue extends Model implements AttributeValueInterface
{
    use HasAbstractRelationships;
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public array $translatable = ['name'];

    protected $fillable = ['name'];

    public array $rules = [
        'code' => ValidationRules::CODE,
    ];

    public $timestamps = false;

    protected static function newFactory(): AttributeValueFactory
    {
        return AttributeValueFactory::new();
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(AttributeInterface::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductInterface::class);
    }
}
