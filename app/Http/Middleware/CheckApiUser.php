<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2023/10/25
 */

namespace App\Http\Middleware;

use App\Enums\UserStatusEnum;
use Closure;
use App\Models\User;
use App\Traits\RestfulResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 用户端中间件
 */
class CheckApiUser
{
    use RestfulResponse;

    public function handle(Request $request, Closure $next)
    {
        /**
         * @var $user User
         */
        $user = Auth::user();
        if (!$user instanceof User) {
            return $this->error(__('login_token_invalid'), 401);
        }
        if ($user->status == UserStatusEnum::Disabled) {
            return $this->error(__('user_disable'), 401);
        }

        return $next($request);
    }
}
