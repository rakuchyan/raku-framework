<?php

namespace App\Http\Controllers;

use App\Traits\RestfulResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\ControllerMiddlewareOptions;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, RestfulResponse;

    const PAGE_FIRST = 1;
    const PER_PAGE = 10;

    public function getPerPage($page = 0)
    {
        return request()->input('per_page', $page ?: self::PER_PAGE);
    }

    public function getPage()
    {
        return request()->input('page', self::PAGE_FIRST);
    }

    public function hasPermissionOr($permission): ControllerMiddlewareOptions
    {
        return $this->middleware('permission:' . implode('|', is_array($permission) ? $permission : func_get_args()));
    }
}
