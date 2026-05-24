<?php

declare(strict_types=1);

namespace Gildsmith\Product\Resources;

use Gildsmith\Product\Models\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Blueprint
 */
class BlueprintResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'name' => $this->getTranslations('name'),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'deleted_at' => $this->deleted_at?->getTimestamp(),
        ];
    }
}
