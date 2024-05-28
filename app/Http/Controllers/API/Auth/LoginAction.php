<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAction extends Controller
{
    #[OA\Get(
        path: "/v1/api/login",
        summary: "Login user",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/User")
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
            new OA\Response(response: 403, description: "Forbidden"),
        ]
    )]
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'token' => $user->createToken('ApiToken')->plainTextToken,
                'user' => $user,
            ]);
        }

        return response()->json([
            'error' => 'Invalid credentials',
        ]);
    }
}
