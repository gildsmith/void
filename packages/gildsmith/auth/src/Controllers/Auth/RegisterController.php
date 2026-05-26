<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers\Auth;

use Gildsmith\Auth\Models\User;
use Gildsmith\Auth\Requests\Auth\RegisterRequest;
use Gildsmith\Auth\Resources\TokenResource;
use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use LogicException;

class RegisterController extends Controller
{
    /**
     * @throws LogicException
     * @throws ValidationException
     */
    public function __invoke(RegisterRequest $request, UserFacadeInterface $users): JsonResponse
    {
        /** @var User $user */
        $user = $users->create($request->validated());

        return new TokenResource($user, $users->issueToken($user))
            ->response()
            ->setStatusCode(201);
    }
}
