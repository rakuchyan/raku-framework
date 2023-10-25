<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    public const ADMIN = 'admin';
    public const PM = 'pm';
    public const DIRECTOR = 'director';
    public const TESTER = 'tester';
    public const DEVELOPER = 'developer';

    public const ROLE_LIST = [
        [
            'name' => self::ADMIN,
            'name_zh' => '管理员',
            'guard_name' => 'api',
            'permissions' => [Permission::ADMIN_WORKBENCH, Permission::FINANCIAL_MANAGEMENT]
        ],
        [
            'name' => self::PM,
            'name_zh' => '项目经理',
            'guard_name' => 'api',
            'permissions' => [Permission::DIRECTOR_WORKBENCH, Permission::EMPLOYEE_WORKBENCH, Permission::BUG_MANAGEMENT, Permission::DOCUMENT_MANAGEMENT, Permission::DASHBOARD, Permission::FINANCIAL_MANAGEMENT]
        ],
        [
            'name' => self::DIRECTOR,
            'name_zh' => '部门主管',
            'guard_name' => 'api',
            'permissions' => [Permission::EMPLOYEE_WORKBENCH, Permission::BUG_MANAGEMENT, Permission::DOCUMENT_MANAGEMENT, Permission::DASHBOARD]
        ], [
            'name' => self::TESTER,
            'name_zh' => '项目测试人员',
            'guard_name' => 'api',
            'permissions' => [Permission::EMPLOYEE_WORKBENCH, Permission::BUG_MANAGEMENT, Permission::DOCUMENT_MANAGEMENT]
        ],
        [
            'name' => self::DEVELOPER,
            'name_zh' => '项目开发人员',
            'guard_name' => 'api',
            'permissions' => [Permission::EMPLOYEE_WORKBENCH, Permission::BUG_MANAGEMENT, Permission::DOCUMENT_MANAGEMENT]
        ]
    ];
}
