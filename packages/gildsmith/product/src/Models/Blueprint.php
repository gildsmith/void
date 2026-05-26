<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models;

use Gildsmith\Contract\Product\AttributeInterface;
use Gildsmith\Contract\Product\BlueprintInterface;
use Gildsmith\Contract\Product\ProductInterface;
use Gildsmith\Product\Database\Factories\BlueprintFactory;
use Gildsmith\Product\Models\Pivots\AttributeBlueprint;
use Gildsmith\Support\Model\Concerns\HasAbstractRelationships;
use Gildsmith\Support\Model\Concerns\HasCode;
use Gildsmith\Support\Model\Concerns\HasImmutableAttributes;
use Gildsmith\Support\Model\Concerns\HasValidationRules;
use Gildsmith\Support\Model\Contracts\HasValidationRulesInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Blueprint extends Model implements BlueprintInterface, HasValidationRulesInterface
{
    use HasAbstractRelationships;
    use HasCode;
    use HasFactory;
    use HasImmutableAttributes;
    use HasTranslations;
    use HasValidationRules;
    use SoftDeletes;

    public $timestamps = false;

    protected array $translatable = ['name'];

    protected array $immutable = ['code'];

    protected $fillable = ['code', 'name'];

    protected static function newFactory(): BlueprintFactory
    {
        return BlueprintFactory::new();
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(AttributeInterface::class)
            ->using(AttributeBlueprint::class)
            ->as('blueprintAttribute')
            ->withPivot('required');
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductInterface::class);
    }

    public function allows(string ...$properties): bool
    {
        $properties = array_values(array_unique($properties));

        if ($properties === []) {
            return true;
        }

        $count = $this->attributes()->whereIn('code', $properties)->count();

        return $count === count($properties);
    }

    public function requires(string ...$properties): bool
    {
        $properties = array_values(array_unique($properties));

        if ($properties === []) {
            return true;
        }

        $count = $this->attributes()
            ->wherePivot('required', true)
            ->whereIn('code', $properties)
            ->count();

        return $count === count($properties);
    }
}
