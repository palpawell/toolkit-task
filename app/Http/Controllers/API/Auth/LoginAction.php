<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Info(title: "My API", version: "1.0.0")]
class LoginAction extends Controller
{

    #[OA\Post(
        path: "/api/v1/login",
        summary: "Login user",
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", description: "User email", example: "john@gmail.com"),
                    new OA\Property(property: "password", type: "string", description: "User password", example: "P@ssw0rd1234")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string", description: "User token", example: "Token1234"),
                        new OA\Property(property: "user", type: "object", description: "User object", example: "{id: 1, email: 'john@gmail.com'}")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
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
