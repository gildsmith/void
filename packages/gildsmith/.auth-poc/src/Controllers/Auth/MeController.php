<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers\Auth;

use Gildsmith\Auth\Models\User;
use Gildsmith\Auth\Resources\UserResource;
use Gildsmith\Contract\User\UserInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MeController extends Controller
{
    /**
     * @throws HttpException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        abort_unless($user instanceof Model && $user instanceof UserInterface, 401);

        /** @var User $user */
        return new UserResource($user)->response();
    }
}
