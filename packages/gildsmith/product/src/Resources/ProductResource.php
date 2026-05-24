<?php

declare(strict_types=1);

namespace Gildsmith\Product\Resources;

use Gildsmith\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'name' => $this->getTranslations('name'),
            'is_complete' => $this->is_complete,
            'blueprint' => BlueprintResource::make($this->whenLoaded('blueprint')),
            'attribute_values' => AttributeValueResource::collection($this->whenLoaded('attributeValues')),
            'collections' => ProductCollectionResource::collection($this->whenLoaded('collections')),
            'created_at' => $this->created_at?->getTimestamp(),
            'updated_at' => $this->updated_at?->getTimestamp(),
            'deleted_at' => $this->deleted_at?->getTimestamp(),
        ];
    }
}
