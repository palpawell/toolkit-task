<?php

namespace App\Http\Controllers\API\Statement;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Statement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ViewAction extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request)
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
            $model = Statement::find($request->query('id'));

            if ($model->user_id !== $request->user()->id) {
                return response()->json([
                    'error' => 'You don\'t have permission to view this statement',
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
            'data' => $model,
        ]);
    }
}
