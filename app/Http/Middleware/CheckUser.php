<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2023/10/25
 */

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Traits\RestfulResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 用户端中间件
 */
class CheckUser
{
    use RestfulResponse;

    public function handle(Request $request, Closure $next)
    {
        /**
         * @var $user User
         */
        $user = Auth::user();
        if (!$user instanceof User) {
            return $this->error('用户登录token无效或已过期', 401);
        }
        if ($user->status == UserStatusEnum::Disabled) {
            return $this->error('您的账号已被禁用', 401);
        }

        return $next($request);
    }
}
