<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Resources;

use Gildsmith\Auth\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Employee
 */
class EmployeeResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at?->timestamp,
            'updated_at' => $this->updated_at?->timestamp,
            'deleted_at' => $this->deleted_at?->timestamp,
        ];
    }
}
