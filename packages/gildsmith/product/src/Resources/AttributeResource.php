<?php

declare(strict_types=1);

namespace Gildsmith\Product\Resources;

use Gildsmith\Product\Models\Attribute;
use Gildsmith\Product\Models\Pivots\AttributeBlueprint;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Attribute
 *
 * @property-read AttributeBlueprint $blueprintAttribute
 */
class AttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'name' => $this->getTranslations('name'),
            'required' => $this->whenPivotLoadedAs('blueprintAttribute', 'attribute_blueprint', fn(): bool => $this->blueprintAttribute->required),
            'values' => AttributeValueResource::collection($this->whenLoaded('values')),
            'blueprints' => BlueprintResource::collection($this->whenLoaded('blueprints')),
            'deleted_at' => $this->deleted_at?->getTimestamp(),
        ];
    }
}
