<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Resources;

use Gildsmith\Auth\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class TokenResource extends JsonResource
{
    public static $wrap = null;

    public function __construct(
        User $resource,
        protected string $token,
    ) {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'token_type' => 'Bearer',
            'access_token' => $this->token,
            'user' => [
                'id' => $this->id,
                'email' => $this->email,
            ],
        ];
    }
}
