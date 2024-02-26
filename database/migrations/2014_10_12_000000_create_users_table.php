<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('account', 100)->comment('账号')->unique('ux_account');
            $table->string('name',100)->comment('姓名');
            $table->string('email')->default('')->nullable()->comment('邮箱');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->comment('密码');
            $table->string('avatar')->nullable()->default('')->comment('头像');
            $table->integer('department_id')->default(0)->comment('部门');
            $table->string('employee_id', 100)->default('')->comment('工号');
            $table->tinyInteger('status')->default(1)->comment('状态 1正常 2禁用');
            $table->text('token')->nullable()->comment('token');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
        Illuminate\Support\Facades\DB::statement("ALTER TABLE `users` COMMENT='用户表'"); // 表注释
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
