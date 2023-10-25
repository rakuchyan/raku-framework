<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    public const ADMIN_WORKBENCH = 'admin-workbench';
    public const DIRECTOR_WORKBENCH = 'director-workbench';
    public const EMPLOYEE_WORKBENCH = 'employee-workbench';
    public const DASHBOARD = 'dashboard';
    public const FINANCIAL_MANAGEMENT = 'financial-management';
    public const BUG_MANAGEMENT = 'bug-management';
    public const DOCUMENT_MANAGEMENT = 'document-management';

    public const PERMISSIONS_LIST = [
        [
            'name' => self::ADMIN_WORKBENCH,
            'name_zh' => '管理员工作台',
            'guard_name' => 'api',
        ],
        [
            'name' => self::DIRECTOR_WORKBENCH,
            'name_zh' => '项目经理工作台',
            'guard_name' => 'api',
        ],
        [
            'name' => self::EMPLOYEE_WORKBENCH,
            'name_zh' => '员工工作台',
            'guard_name' => 'api',
        ],
        [
            'name' => self::DASHBOARD,
            'name_zh' => '看板',
            'guard_name' => 'api',
        ],
        [
            'name' => self::FINANCIAL_MANAGEMENT,
            'name_zh' => '财务管理',
            'guard_name' => 'api',
        ],
        [
            'name' => self::BUG_MANAGEMENT,
            'name_zh' => '缺陷管理',
            'guard_name' => 'api',
        ],
        [
            'name' => self::DOCUMENT_MANAGEMENT,
            'name_zh' => '文档管理',
            'guard_name' => 'api',
        ]
    ];
}
