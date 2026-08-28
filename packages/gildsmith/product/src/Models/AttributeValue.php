<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Database\Factories\AttributeValueFactory;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Model\Concerns\HasCode;
use Gildsmith\Support\Model\Concerns\HasImmutableAttributes;
use Gildsmith\Support\Model\Concerns\HasValidationRules;
use Gildsmith\Support\Model\Contracts\HasValidationRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class AttributeValue extends Model implements AttributeValueInterface, HasValidationRulesInterface
{
    use HasAbstractRelationships;
    use HasCode;
    use HasFactory;
    use HasImmutableAttributes;
    use HasTranslations;
    use HasValidationRules;
    use SoftDeletes;

    public array $translatable = ['name'];
    public $timestamps = false;
    protected array $immutable = ['code'];
    protected $fillable = ['code', 'name', 'attribute_id'];

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
