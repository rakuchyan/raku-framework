<?php
/**
 * Author: raku <leli@mufan.design>
 * Date: 2023/10/25
 */

namespace App\Http\Middleware;

use App\Enums\AdminUserActive;
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
        if (!$user instanceof AdminUser) {
            $this->error(__('login_token_invalid'), 401, [], 401);
        }

        if ($user->status == AdminUserActive::Disabled) {
            return $this->error(__('user_disable'), 401, [], 401);
        }

        if ($user->deleted_at) {
            return $this->error(__('user_disable'), 401, [], 401);
        }

        return $next($request);
    }
}
