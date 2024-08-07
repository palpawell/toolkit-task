<?php

use App\Http\Controllers\API\Auth\LoginAction;
use App\Http\Controllers\API\Auth\LogoutAction;
use App\Http\Controllers\API\Auth\RefreshAction;
use App\Http\Controllers\API\Auth\RegisterAction;
use App\Http\Controllers\Api\Statement\CreateAction;
use App\Http\Controllers\Api\Statement\DeleteAction;
use App\Http\Controllers\Api\Statement\IndexAction;
use App\Http\Controllers\API\Statement\ViewAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::post('login', LoginAction::class);
    Route::post('register', RegisterAction::class);
    Route::post('logout', LogoutAction::class)->middleware('auth:sanctum');
    Route::post('refresh', RefreshAction::class)->middleware('auth:sanctum');

    Route::get('statement/index', IndexAction::class)->middleware('auth:sanctum');
    Route::get('statement/view', ViewAction::class)->middleware('auth:sanctum');
    Route::post('statement/create', CreateAction::class)->middleware('auth:sanctum');
    Route::delete('statement/delete', DeleteAction::class)->middleware('auth:sanctum');
});
