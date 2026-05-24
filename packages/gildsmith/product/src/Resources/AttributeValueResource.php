<?php

declare(strict_types=1);

namespace Gildsmith\Product\Resources;

use Gildsmith\Product\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AttributeValue
 */
class AttributeValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'name' => $this->getTranslations('name'),
            'attribute' => AttributeResource::make($this->whenLoaded('attribute')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'deleted_at' => $this->deleted_at?->getTimestamp(),
        ];
    }
}
