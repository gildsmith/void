<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Resources;

use Gildsmith\Auth\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at?->timestamp,
            'last_login_at' => $this->last_login_at?->timestamp,
            'customer' => CustomerResource::make($this->whenLoaded('customer')),
            'employee' => EmployeeResource::make($this->whenLoaded('employee')),
            'created_at' => $this->created_at?->timestamp,
            'updated_at' => $this->updated_at?->timestamp,
            'deleted_at' => $this->deleted_at?->timestamp,
        ];
    }
}
