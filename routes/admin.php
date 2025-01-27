<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2024/2/26
 */

use App\Http\Controllers\CommonController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('test', [TestController::class, 'test']);
Route::get('roles', [RoleController::class, 'index']);
Route::get('dictionaries', [CommonController::class, 'dictionaries']);
// 枚举
Route::get('enums', [CommonController::class, 'getAllEnums']);
Route::get('enums/{tag}', [CommonController::class, 'getEnumsByKey']);
Route::get('upload', [CommonController::class, 'upload']);


Route::middleware(['auth:admin', 'check.admin.user'])->group(function () {
    // 用户相关
    Route::group([], function () {});
});
