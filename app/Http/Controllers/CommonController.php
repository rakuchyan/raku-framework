<?php

namespace App\Http\Controllers;

use App\Enums\AdminUserStatus;
use App\Enums\AdminUserType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    protected array $dict = [
        'admin_user_status' => AdminUserStatus::class,
        'admin_user_type' => AdminUserType::class,
    ];

    public function dictionaries(): JsonResponse
    {
        $res = [];

        foreach ($this->dict as $key => $model) {
            $list = [];
            foreach ($model::cases() as $value) {
                $list[] = ['key' => $value->value, 'label' => method_exists($value, 'desc') ? $value->desc() : $value->name];
            }
            $res[] = [
                'tag' => $key,
                'options' => $list
            ];
        }

        return $this->success($res);
    }
}
