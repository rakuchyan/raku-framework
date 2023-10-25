<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        // 权限
        Permission::query()->upsert(Permission::PERMISSIONS_LIST, ['name', 'guard_name']);
        // 角色
        foreach (Role::ROLE_LIST as $roleInfo) {

            $role = Role::query()->updateOrCreate(Arr::only($roleInfo, ['name', 'guard_name']), Arr::except($roleInfo, ['permissions']));
            /**
             * @var $role Role
             */
            $role->givePermissionTo(Arr::get($roleInfo, 'permissions', []));
        }
        Permission::bootRefreshesPermissionCache();
    }
}
