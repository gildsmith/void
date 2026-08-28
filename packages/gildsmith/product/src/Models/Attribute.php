<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Product\Database\Factories\AttributeFactory;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Model\Concerns\HasCode;
use Gildsmith\Support\Model\Concerns\HasImmutableAttributes;
use Gildsmith\Support\Model\Concerns\HasValidationRules;
use Gildsmith\Support\Model\Contracts\HasValidationRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model implements AttributeInterface, HasValidationRulesInterface
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
    protected $fillable = ['code', 'name'];

    protected static function newFactory(): AttributeFactory
    {
        return AttributeFactory::new();
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValueInterface::class);
    }
}
