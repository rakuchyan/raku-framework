<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\QueryById;
use App\Imports\UserImport;
use App\Models\User;
use App\Traits\RestfulResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use RestfulResponse;

    public const ADMIN_LOG_NAMES = [
        'login' => '登录',
        'logout' => '注销',
    ];

    /**
     * 用户详情，request 用法demo
     *
     * @Author raku
     *
     * @param QueryById $request
     * @return void
     */
    public function detail(QueryById $request)
    {
        $userId = $request->id;
    }

    public function info(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        // 当前用户权限
        $user->role_name = $user->getRoleNames();
        $user->all_permissions = $user->getAllPermissions()
            ->map(function ($v) {
                return $v->only(['id', 'name', 'name_zh']);
            })->toArray();
        // 隐藏角色
        $user->makeHidden(['permissions', 'roles']);
        $user->department = $user->department()->firstOrNew()->only(['name', 'name_en']);

        return $this->success($user);
    }

    public function login(Request $request)
    {
        $rules = [
            'account' => 'required|string',
            'password' => 'required|string'
        ];
        $validated = $request->validate($rules);

        /**
         * @var User $user
         */
        if (!$userInfo = User::query()->where(['account' => $validated['account']])->first()) {
            return $this->error('用户不存在');
        }

        if (!$token = auth('api')->attempt($validated)) {
            return $this->error('密码错误');
        }

        try {
            $userInfo->token = 'Bearer ' . $token;
            $userInfo->save();

            unset($userInfo->password);
            unset($userInfo->token);
            // 给登录日志用
            Auth::onceUsingId($userInfo->id);

            return $this->respondWithToken($token);
        } catch (\Exception $exception) {
            info($exception->getMessage());

            return $this->error('操作失败，请稍后重试');
        }

        // $user = User::query()->find(1);
        // return $this->success($user->toArray());
        // dd(
        // $user->hasPermissionTo('parts'),
        // $user->getAllPermissions()->toArray(),
        // $user->hasRole('admin'),
        // $user->assignRole('admin'),
        // $user->hasRole('admin')
        // );

    }

    public function logout()
    {
        $id = Auth::id();
        auth('api')->logout();
        Auth::onceUsingId($id);
        return $this->success();
    }

    public function importUser(Request $request)
    {
        try {
            $result = Excel::import(new UserImport(), $request->file('excelfile'));
            if ($result) {
                return $this->success();
            }
        } catch (\Throwable $e) {
            info('导入失败.' . $e->getMessage());
            return $this->error('导入失败');
        }
    }

    protected function respondWithToken($token): JsonResponse
    {
        return $this->success([
            'token' => 'Bearer ' . $token,
            'expires_in' => auth('api')->factory()->getTTL(7200)
        ]);
    }
}
