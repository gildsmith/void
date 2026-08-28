<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers;

use Gildsmith\Auth\Requests\RegisterRequest;
use Gildsmith\Auth\Support\SessionManager;
use Gildsmith\Support\Facades\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request, SessionManager $sessions): JsonResponse
    {
        $user = User::register($request->safe()->only(['email', 'password']));

        $token = $sessions->create(
            user: $user,
            remember: $request->boolean('remember'),
            name: $request->validated('name'),
        );

        return response()->json($token->toResponseArray($user), 201);
    }
}
