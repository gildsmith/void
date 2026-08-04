<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers\Auth;

use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Gildsmith\Contract\User\UserInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LogoutController extends Controller
{
    /**
     * @throws HttpException
     */
    public function __invoke(Request $request, UserFacadeInterface $users): Response
    {
        $user = $request->user();
        $token = $request->bearerToken();

        abort_unless($user instanceof Model && $user instanceof UserInterface && $token !== null, 401);

        $users->logout($user, $token);

        return response()->noContent();
    }
}
