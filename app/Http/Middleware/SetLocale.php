<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->header('locale', 'en');
        if (in_array($locale, ['en', 'zh_cn'])) {
            App::setLocale($locale);
        }
        return $next($request);
    }
}
