<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers;

use Gildsmith\Auth\Models\Session;
use Gildsmith\Auth\Support\SessionManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class SessionIndexController extends Controller
{
    public function __invoke(Request $request, SessionManager $sessions): JsonResponse
    {
        $currentSession = $sessions->currentSession($request);

        $activeSessions = Session::query()
            ->active()
            ->where('user_id', Auth::guard('gildsmith')->id())
            ->latest('last_used_at')
            ->latest()
            ->get()
            ->each(function (Session $session) use ($currentSession): void {
                $session->setAttribute('current', $currentSession?->is($session) ?? false);
            });

        return response()->json(['sessions' => $activeSessions]);
    }
}
