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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('account', 100)->comment('账号');
            $table->string('name',100)->comment('姓名');
            $table->string('nickname',100)->comment('昵称');
            $table->string('phone', 50)->comment('手机号');
            $table->string('email')->default('')->nullable()->comment('邮箱');
            $table->string('password')->comment('密码');
            $table->string('avatar')->nullable()->default('')->comment('头像');
            $table->integer('department_id')->default(0)->comment('部门');
            $table->boolean('active')->default(true)->comment('是否启用');
            $table->boolean('is_manager')->default(false)->comment('是否为部门经理');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->comment('后台用户表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
};
