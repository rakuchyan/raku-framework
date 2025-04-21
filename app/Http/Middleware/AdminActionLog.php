<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AdminActionLog
{
    protected function getName($className, $function)
    {
        // 获取类的常量列表
        if (defined("{$className}::ADMIN_LOG_NAMES")) {
            return Arr::get($className::ADMIN_LOG_NAMES, $function, null);
        }
        return null;
    }

    public function handle(Request $request, Closure $next)
    {
        /**
         * @var  \Illuminate\Http\JsonResponse $response
         * @var  User $user
         */
        $response = $next($request);
        $function = Route::current()->getActionMethod();
        $className = Str::before(Route::current()->getActionName(), "@{$function}");
        $name = $this->getName($className, $function);
        // 只记录需要记录的
        // if (!$name) {
        //     return $response;
        // }
        if (!$name || (isset($content['code']) && $content['code'] !== 200)) {
            return $response;
        }

        $user = Auth::guard('admin')->user();
        $data = [
            'url' => $request->url(),
            'method' => $request->getMethod(),
            'name' => $name,
            'data' => $name == '登录' ? [] : $request->all(),
            'status' => $response->status(),
        ];
        if ($user instanceof User) {
            dispatch(function () use ($user, $data) {
                $user->logs()->create($data);
            });
        }
        return $response;
    }
}
