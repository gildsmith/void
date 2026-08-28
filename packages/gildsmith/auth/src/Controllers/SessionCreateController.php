<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers;

use Gildsmith\Auth\Requests\SessionCreateRequest;
use Gildsmith\Auth\Support\SessionManager;
use Gildsmith\Support\Facades\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class SessionCreateController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(SessionCreateRequest $request, SessionManager $sessions): JsonResponse
    {
        $user = User::login(
            (string) $request->validated('email'),
            (string) $request->validated('password'),
        );

        if ($user === null) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $token = $sessions->create(
            user: $user,
            remember: $request->boolean('remember'),
            name: $request->validated('name'),
        );

        return response()->json($token->toResponseArray($user));
    }
}
