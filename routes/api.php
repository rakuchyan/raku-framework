<?php

use App\Http\Controllers\UserController;
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

Route::post('import-user', [UserController::class, 'importUser']);
Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:api', 'check.user'])->group(function () {

    Route::get('info', [UserController::class, 'info']);
    Route::post('logout', [UserController::class, 'logout']);

    // 管理员角色路由
    Route::group(['middleware' => ['role:admin'], 'prefix' => 'admin'], function () {

    });

    // 项目经理角色路由
    Route::group(['middleware' => ['role:pm'], 'prefix' => 'pm'], function () {


    });

    // 部门主管角色路由
    Route::group(['middleware' => ['role:director', 'prefix' => 'director']], function () {

    });

    // 项目测试人员角色路由
    Route::group(['middleware' => ['role:tester', 'prefix' => 'tester']], function () {

    });

    // 项目开发人员角色路由
    Route::group(['middleware' => ['role:developer', 'prefix' => 'developer']], function () {

    });
});

