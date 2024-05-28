<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutAction extends Controller
{
    /**
     * Logout user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'User successfully logged out',
        ]);
    }
}
