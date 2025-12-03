<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\AttributeValueInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Product\Database\Factories\AttributeFactory;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Utils\ValidationRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model implements AttributeInterface
{
    use HasAbstractRelationships;
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;

    public array $translatable = ['name'];

    protected $fillable = ['code', 'name'];

    public $timestamps = false;

    public array $rules = [
        'code' => ValidationRules::CODE,
    ];

    public function blueprints(): BelongsToMany
    {
        return $this->belongsToMany(BlueprintInterface::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValueInterface::class);
    }

    protected static function newFactory(): AttributeFactory
    {
        return AttributeFactory::new();
    }
}
