<?php

declare(strict_types=1);

namespace Gildsmith\Product\Resources;

use Gildsmith\Product\Models\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductCollection
 */
class ProductCollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'type' => $this->type,
            'name' => $this->getTranslations('name'),
            'products' => ProductResource::collection($this->whenLoaded('products')),
            'created_at' => $this->created_at?->getTimestamp(),
            'updated_at' => $this->updated_at?->getTimestamp(),
            'deleted_at' => $this->deleted_at?->getTimestamp(),
        ];
    }
}
