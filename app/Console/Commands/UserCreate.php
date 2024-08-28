<?php

namespace App\Console\Commands;

use App\Enums\UserStatusEnum;
use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UserCreate extends Command
{

    protected $signature = 'user:create';


    protected $description = '快速创建用户并生成token';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        start:
        do {
            $account = $this->ask('请输入账号名');
            $exists = User::query()->where('account', $account)->exists();
            if ($exists) {
                $this->error("账号名重复,请重新输入");
            }
        } while ($exists);
        $password = $this->ask('请输入密码');
        $this->info("请选择角色:");
        $roles = Role::query()->pluck('name_zh', 'id');
        foreach ($roles as $id => $role) {
            $this->info(" ID:{$id} - {$role}");
        }
        $askRoles = explode(',', $this->ask('选择角色ID(多个用,分割)'));
        $email = $this->ask('请输入邮箱');

        /**
         * @varUser $user
         */
        DB::beginTransaction();
        try {
            $user = User::query()
                ->create([
                    'account' => $account,
                    'name' => $account,
                    'password' => bcrypt($password),
                    'email' => $email,
                    'status' => UserStatusEnum::Activated,
                ]);
            $user->syncRoles($askRoles);

            $ttl = 365 * 24 * 60;
            $token = auth('api')->setTTL($ttl)->login($user);
            $this->info('Bearer ' . $token);
            $user->token = $token;
            $user->save();

            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            $this->error("参数异常,请重试" . $throwable->getMessage());
            goto start;
        }
        $this->info("添加成功");
    }
}
