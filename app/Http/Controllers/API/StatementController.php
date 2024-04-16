<?php

namespace App\Http\Controllers\API;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
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
     * Get list of statements for specific user role
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $models = Statement::query();

        if (!$user->hasRole(UserRole::ADMIN)) {
            $models = $models->where([
                'user_id' => $request->user()->id,
            ]);
        }

        return response()->json(['models' => $models->paginate(25)]);
    }

    /**
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

    /**
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'msg' => $validator->errors(),
                ],
            ]);
        }

        try {
            $model = Statement::findOrFail($request->query('id'));

            if ($model->user_id !== $request->user()->id) {
                return response()->json([
                    'error' => 'You don\'t have permission to delete this statement',
                ]);
            }

            $model->delete();
        } catch (\Throwable $t) {
            return response()->json([
                'success' => false,
                'data' => [
                    'msg' => $t->getMessage(),
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'msg' => 'Receipt deleted successfully',
            ],
        ]);
    }
}
