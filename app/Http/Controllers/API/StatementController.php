<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatementCollection;
use App\Models\Statement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @return StatementCollection|JsonResponse
     */
    public function index(Request $request)
    {
        $models = Statement::query()->where([
            'user' => $request->user()->id,
        ]);

        return response()->json(['models' => $models]);
    }

    /**
     * Create statement
     *
     * @return JsonResponse
     */
    public function create(Request $request)
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
