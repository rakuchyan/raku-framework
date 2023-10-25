<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UserImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    // 'column_name_in_database' => 'Column Name in Excel'
    protected static array $header = [
        'name' => 'Name',
        'account' => 'Account',
        'department' => 'Department',
        'employee' => 'Employee',
        'email' => 'Email',
        'role' => 'Role',
    ];

    public function sheets(): array
    {
        return [
            0 => new self(),
        ];
    }

    public function model(array $row)
    {
        // 移除整数键
        $filteredData = array_filter($row, function ($key) {
            return is_string($key);
        }, ARRAY_FILTER_USE_KEY);

        $department = Department::query()->where('name', $row['department'])->first();
        $role = Role::query()->where('name', $row['role'])->first();

        if (!$department || !$role) {
            info('导入失败.', $row);
            return false;
        }

        $filteredData['department_id'] = $department->id;
        $filteredData['employee_id'] = $filteredData['employee'];
        $filteredData['status'] = User::STATUS_NORMAL;
        $filteredData['password'] = bcrypt(123456);

        $filteredData = Arr::except($filteredData, ['department', 'employee', 'role']);

        $user = new User($filteredData);
        $user->syncRoles($role);
        $user->save();
    }
}
