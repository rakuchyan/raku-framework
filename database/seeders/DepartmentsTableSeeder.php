<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            ['name_en' => 'Finance', 'name' => '财务部'],
            ['name_en' => 'Product Management', 'name' => '产品管理部'],
            ['name_en' => 'Research & Development', 'name' => '研发中心'], // 1级
            ['name_en' => 'Manufacturing center', 'name' => '制造交付中心'],
            ['name_en' => 'Sales center', 'name' => '销售中心'],
            ['name_en' => 'Supply chain', 'name' => '供应链部'],
            ['name_en' => 'Personnel', 'name' => '人力资源管理部'],
            ['name_en' => 'Quality', 'name' => '质量部'],
            ['name_en' => 'GM Office', 'name' => '总经办'],
            ['name_en' => '', 'name' => '售后部'],
        ];

        foreach ($departments as $department) {
            $department['created_at'] = now();
            $department['updated_at'] = now();
            DB::table('departments')->insert($department);
        }

        // 获取研发中心的ID
        $rdc_id = DB::table('departments')->where('name', '研发中心')->first()->id;

        // 研发中心的二级部门
        $sub_departments = [
            ['name_en' => '', 'name' => '硬件射频部', 'parent_id' => $rdc_id],
            ['name_en' => '', 'name' => '软件部', 'parent_id' => $rdc_id],
            ['name_en' => '', 'name' => '项目管理部', 'parent_id' => $rdc_id],
            ['name_en' => '', 'name' => '测试部', 'parent_id' => $rdc_id],
            ['name_en' => '', 'name' => '研发管理部', 'parent_id' => $rdc_id],
            ['name_en' => '', 'name' => '结构部', 'parent_id' => $rdc_id],
            ['name_en' => '', 'name' => '视觉识别部', 'parent_id' => $rdc_id],
            ['name_en' => '', 'name' => '创新项目组', 'parent_id' => $rdc_id],
        ];

        foreach ($sub_departments as $sub_department) {
            $sub_department['created_at'] = now();
            $sub_department['updated_at'] = now();
            DB::table('departments')->insert($sub_department);
        }
    }
}
