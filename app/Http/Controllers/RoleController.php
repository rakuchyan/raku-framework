<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->success(Collection::make(Role::query()->select(['id', 'name', 'name_zh'])->get()));

    }
}
