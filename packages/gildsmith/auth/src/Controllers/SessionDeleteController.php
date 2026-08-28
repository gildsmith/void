<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers;

use Gildsmith\Auth\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class SessionDeleteController extends Controller
{
    public function __invoke(Request $request, string $session): Response
    {
        /** @var Session $authSession */
        $authSession = Session::query()->findOrFail($session);
        $userId = Auth::guard('gildsmith')->id();

        abort_unless((int) $authSession->user_id === (int) $userId, 404);

        $authSession->revoke();
        Auth::guard('gildsmith')->forgetUser();

        return response()->noContent();
    }
}
