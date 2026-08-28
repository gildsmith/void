<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Support;

use Gildsmith\Auth\Data\SessionToken;
use Gildsmith\Auth\Exceptions\UserNotFoundException;
use Gildsmith\Auth\Models\Session;
use Gildsmith\Auth\Models\User;
use Gildsmith\Contract\User\UserInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class SessionManager
{
    private const TOKEN_LENGTH = 80;

    /**
     * @throws UserNotFoundException
     */
    public function create(
        UserInterface $user,
        bool $remember = false,
        ?string $name = null,
    ): SessionToken {
        $user = $this->resolveUserModel($user);
        $token = Str::random(self::TOKEN_LENGTH);

        $session = Session::query()->create([
            'user_id' => $user->getKey(),
            'name' => $name,
            'token_hash' => $this->hash($token),
            'remember' => $remember,
            'last_used_at' => now(),
            'expires_at' => now()->addMinutes($this->lifetime($remember)),
        ]);

        return new SessionToken($session, $token);
    }

    public function userFromRequest(Request $request): ?Authenticatable
    {
        $session = $this->sessionFromRequest($request);

        if ($session === null) {
            return null;
        }

        $request->attributes->set('gildsmith.session', $session);

        $user = $session->user;

        return $user instanceof Authenticatable ? $user : null;
    }

    public function sessionFromRequest(Request $request): ?Session
    {
        $token = $request->bearerToken();

        if ($token === null || $token === '') {
            return null;
        }

        /** @var Session|null $session */
        $session = Session::query()
            ->with('user')
            ->where('token_hash', $this->hash($token))
            ->first();

        if ($session === null || !$session->isActive()) {
            return null;
        }

        $session->forceFill(['last_used_at' => now()])->save();

        return $session->refresh()->load('user');
    }

    public function currentSession(Request $request): ?Session
    {
        $session = $request->attributes->get('gildsmith.session');

        return $session instanceof Session ? $session : null;
    }

    private function hash(string $token): string
    {
        return hash('sha256', $token);
    }

    private function lifetime(bool $remember): int
    {
        $key = $remember
            ? 'gildsmith.auth.sessions.remember_lifetime'
            : 'gildsmith.auth.sessions.lifetime';

        $fallback = $remember ? 43200 : 120;

        return (int) config($key, $fallback);
    }

    /**
     * @throws UserNotFoundException
     */
    private function resolveUserModel(UserInterface $user): User
    {
        if ($user instanceof User && $user->exists) {
            return $user;
        }

        return User::query()
            ->withTrashed()
            ->where('email', $user->code)
            ->first()
            ?? throw new UserNotFoundException($user->code);
    }
}
