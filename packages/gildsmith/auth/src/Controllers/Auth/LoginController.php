<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers\Auth;

use Gildsmith\Auth\Models\User;
use Gildsmith\Auth\Requests\Auth\LoginRequest;
use Gildsmith\Auth\Resources\TokenResource;
use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Gildsmith\Support\Exceptions\MissingSoftDeletesException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use LogicException;

class LoginController extends Controller
{
    /**
     * @throws LogicException
     * @throws MissingSoftDeletesException
     * @throws ValidationException
     */
    public function __invoke(LoginRequest $request, UserFacadeInterface $users): JsonResponse
    {
        /** @var User|null $user */
        $user = $users->login(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        if ($user === null) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return new TokenResource($user, $users->issueToken($user))->response();
    }
}
