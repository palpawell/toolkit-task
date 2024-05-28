<?php

namespace App\Http\Controllers\Api\Statement;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Statement;
use Illuminate\Http\Request;

class IndexAction extends Controller
{
    public function __invoke(Request $request)
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
}
