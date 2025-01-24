<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('test', [TestController::class, 'test']);

Route::post('import-user', [UserController::class, 'importUser']);
Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:api', 'check.user'])->group(function () {

    Route::get('info', [UserController::class, 'info']);
    Route::post('logout', [UserController::class, 'logout']);


    // 用户相关
    Route::group([], function () {
        // 用户信息
        Route::get('user', [UserController::class, 'info']);
        // 更改个人信息
        Route::put('user', [UserController::class, 'editInfo']);
        // 重置密码
        Route::post('user/reset-pwd', [UserController::class, 'resetPassword']);
        // 登出
        Route::post('logout', [UserController::class, 'logout']);

    });
});

