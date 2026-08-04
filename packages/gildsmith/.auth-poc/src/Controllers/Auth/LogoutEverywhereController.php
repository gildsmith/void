<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers\Auth;

use Gildsmith\Contract\Facades\Auth\UserFacadeInterface;
use Gildsmith\Contract\User\UserInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use LogicException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LogoutEverywhereController extends Controller
{
    /**
     * @throws HttpException
     * @throws LogicException
     */
    public function __invoke(Request $request, UserFacadeInterface $users): Response
    {
        $user = $request->user();

        abort_unless($user instanceof Model && $user instanceof UserInterface, 401);

        $users->logoutEverywhere($user);

        return response()->noContent();
    }
}
