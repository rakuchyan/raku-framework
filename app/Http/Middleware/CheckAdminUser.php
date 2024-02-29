<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2023/10/25
 */

namespace App\Http\Middleware;

use App\Enums\AdminUserStatus;
use Closure;
use App\Utils\CommonUtil;
use App\Traits\RestfulResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 用户端中间件
 */
class CheckAdminUser
{
    use RestfulResponse;

    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->status == AdminUserStatus::Disabled->value) {
            return $this->error('您的账号已被禁用', 401);
        }

        $response = $next($request);

        CommonUtil::curlCommand($request);

        return $response;
    }
}
