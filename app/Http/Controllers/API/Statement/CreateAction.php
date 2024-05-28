<?php

namespace App\Http\Controllers\Api\Statement;

use App\Http\Controllers\Controller;
use App\Models\Statement;
use Illuminate\Http\Request;

class CreateAction extends Controller
{
    /**
     * Handle the incoming request.
     */
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
