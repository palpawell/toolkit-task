<?php

namespace App\Http\Controllers\Api\Statement;

use App\Http\Controllers\Controller;
use App\Models\Statement;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    description: "Enter your bearer token in the format **Bearer &lt;token&gt;**",
    bearerFormat: "JWT",
    scheme: "bearer"
)]
#[OA\Post(
    path: "/api/v1/statement/create",
    summary: "Create new statement",
    security: [["bearerAuth" => []]],
    requestBody: new OA\RequestBody(
        content: new OA\JsonContent(
            required: ["title"],
            properties: [
                new OA\Property(property: "title", description: "Statement title", type: "string", example: "My statement title"),
                new OA\Property(property: "file", description: "Uploaded file", type: "string", example: "file.pdf"),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: "Successful operation",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "id", description: "Statement Id", type: "integer", example: "1"),
                ]
            )
        ),
        new OA\Response(response: 401, description: "Unauthenticated"),
    ]
)]
class CreateAction extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'msg' => $validator->errors(),
                ],
            ]);
        }

        try {
            $statement = new Statement();
            $statement->title = $request->title;
            $statement->user_id = $request->user()->id;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = $file->getClientOriginalName();

                $file->storeAs('public/uploads', $fileName);

                $statement->file = $fileName;
            }

            $statement->save();
        } catch (\Throwable $t) {
            return response()->json([
                'data' => [
                    'msg' => $t->getMessage(),
                ],
            ]);
        }

        return response()->json([
            'statement' => $statement,
        ]);
    }
}
