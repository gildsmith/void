<?php

declare(strict_types=1);

namespace Gildsmith\Product\Models\Pivots;

use Gildsmith\Product\Models\AttributeValue;
use Gildsmith\Product\Models\Product;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

/**
 * @property-read int $id
 * @property int $attribute_id
 * @property int $blueprint_id
 * @property bool $required
 */
class AttributeBlueprint extends Pivot
{
    public $timestamps = false;

    protected $table = 'attribute_blueprint';

    protected $casts = [
        'required' => 'bool',
    ];

    protected static function booted(): void
    {
        static::created(function (AttributeBlueprint $pivot) {
            if ($pivot->required) {
                $pivot->markBlueprintProductsIncomplete();
            }
        });

        static::updated(function (AttributeBlueprint $pivot) {
            if ($pivot->wasChanged('required') && $pivot->required) {
                $pivot->markBlueprintProductsIncomplete();
            }
        });

        static::deleting(fn (AttributeBlueprint $pivot) => $pivot->cascadeProductAttributeValues());
    }

    private function markBlueprintProductsIncomplete(): void
    {
        Product::query()
            ->where('blueprint_id', $this->blueprint_id)
            ->update(['is_complete' => false]);
    }

    private function cascadeProductAttributeValues(): void
    {
        DB::table('attribute_value_product')
            ->whereIn('product_id', Product::query()
                ->select('id')
                ->where('blueprint_id', $this->blueprint_id))
            ->whereIn('attribute_value_id', AttributeValue::query()
                ->select('id')
                ->where('attribute_id', $this->attribute_id))
            ->delete();
    }
}
