<?php

declare(strict_types=1);

namespace Gildsmith\Product\Resources;

use Gildsmith\Product\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Attribute
 */
class AttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'name' => $this->getTranslations('name'),
            'values' => AttributeValueResource::collection($this->whenLoaded('values')),
            'deleted_at' => $this->deleted_at?->getTimestamp(),
        ];
    }
}
