<?php

declare(strict_types=1);

namespace Gildsmith\Auth\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CurrentUserController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }
}
