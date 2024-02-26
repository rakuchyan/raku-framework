<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Traits\RestfulResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    use  RestfulResponse;

    public function __construct()
    {
        // 仅可访问
        // $this->hasRoleOr(Role::ADMIN)->only(['store']);
    }

    public function test(Request $request)
    {
        return $this->success([]);
    }
}
