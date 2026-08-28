<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers;

use Gildsmith\Auth\Support\SessionManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CurrentSessionDeleteController extends Controller
{
    public function __invoke(Request $request, SessionManager $sessions): Response
    {
        $sessions->currentSession($request)?->revoke();
        Auth::guard('gildsmith')->forgetUser();

        return response()->noContent();
    }
}
