<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2024/2/26
 */

use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('test', [TestController::class, 'test']);

Route::middleware(['auth:admin', 'check.admin.user'])->group(function () {

});
