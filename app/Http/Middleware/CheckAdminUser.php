<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2023/10/25
 */

namespace App\Http\Middleware;

use App\Enums\AdminUserStatus;
use App\Models\AdminUser;
use Closure;
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
        /**
         * @var $user AdminUser
         */
        $user = Auth::user();
        if ($user->status == AdminUserStatus::Disabled) {
            return $this->error('您的账号已被禁用', 401);
        }

        return $next($request);
    }
}
