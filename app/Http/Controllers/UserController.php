<?php

namespace App\Http\Controllers;

use App\Enums\AdminUserType;
use App\Http\Requests\User\QueryById;
use App\Imports\UserImport;
use App\Models\User;
use App\Traits\RestfulResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public const ADMIN_LOG_NAMES = [
        'login' => '登录',
        'logout' => '注销',
    ];

    /**
     * 用户详情，request 用法demo
     *
     *
     * @param QueryById $request
     * @return void
     */
    public function detail(QueryById $request)
    {
        $userId = $request->id;
    }

    /**
     * 个人用户信息
     *
     *
     * @return JsonResponse
     */
    public function info(): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        // 部门
        $user->department = $user->loadMissing(['department:id,name,parent_id', 'department.parent:id,name']);

        // 当前用户权限
        $user->role_name = $user->getRoleNames();
        $user->all_permissions = $user->getAllPermissions()
            ->map(function ($v) {
                return $v->only(['id', 'name', 'name_zh']);
            })->toArray();
        // 隐藏角色
        $user->makeHidden(['permissions', 'roles']);

        return $this->success($user);
    }

    public function login(Request $request)
    {
        $rules = [
            'user_type' => ['required', new Enum(AdminUserType::class)],
            'account' => 'required|string',
            'password' => 'required|string'
        ];
        $validated = $request->validate($rules);

        /**
         * @var User $user
         */
        if (!$userInfo = User::query()->where(['account' => $validated['account'], 'user_type' => $validated['user_type']])->first()) {
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

            return $this->respondWithToken($token, $userInfo);
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

    /**
     * 个人修改信息
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editInfo(Request $request): JsonResponse
    {
        $rules = [
            'avatar' => 'nullable|string|max:200',
        ];
        $validated = $request->validate($rules);

        $user = auth()->user();
        $user->avatar = $validated['avatar'];
        $user->save();

        return $this->success();
    }

    /**
     * 个人重置密码
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $rules = [
            'new_password' => 'required|min:8',
            'old_password' => 'required',
        ];
        $validated = $request->validate($rules, [
            'password.required' => '修改密码不能为空',
            'old_password.required' => '旧密码不能为空',
        ]);

        $password = $validated['new_password'];
        $oldPassword = $validated['old_password'];

        $user = auth()->user();
        $organ_id = auth()->id();
        $formerPassword = $user->getAuthPassword();
        if (Hash::check($oldPassword, $formerPassword)) {
            // 对比旧密码的是否与当前一致
            $organUpdate = User::where('id', $organ_id)->update(['password' => bcrypt($password)]);
            if ($organUpdate) {
                return $this->success();
            }
            return $this->error('修改失败');
        }
        return $this->error('旧密码有误');

    }

    /**
     * 管理员删除用户
     *
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return $this->success();
    }

    protected function respondWithToken($token, $user): JsonResponse
    {
        // return $this->success([
        //     'token' => 'Bearer ' . $token,
        //     'expires_in' => auth('api')->factory()->getTTL(7200)
        // ]);

        // 当前用户权限
        $user->role_name = $user->getRoleNames();
        $user->all_permissions = $user->getAllPermissions()
            ->map(function ($v) {
                return $v->only(['id', 'name', 'name_zh']);
            })->toArray();
        // 隐藏角色
        $user->makeHidden(['permissions', 'roles']);

        return $this->success([
            'user' => $user,
            'token' => 'Bearer ' . $token,
            'expires_in' => auth('api')->factory()->getTTL()
        ]);
    }
}
