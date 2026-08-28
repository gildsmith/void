<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Data;

use Gildsmith\Auth\Models\Session;
use Gildsmith\Contract\User\UserInterface;

final readonly class SessionToken
{
    public function __construct(
        public Session $session,
        public string $token,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toResponseArray(UserInterface $user): array
    {
        return [
            'token_type' => 'Bearer',
            'token' => $this->token,
            'expires_at' => $this->session->expires_at->toISOString(),
            'session' => $this->session,
            'user' => $user,
        ];
    }
}
