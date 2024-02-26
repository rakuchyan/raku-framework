<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();$table->bigInteger('user_id')->index()->comment('后台用户id');
            $table->string('url')->comment('url');
            $table->string('method')->comment('请求方法');
            $table->json('data')->nullable()->comment('数据');
            $table->string('name')->nullable()->comment('名称');
            $table->string('status')->comment('返回码');
            $table->softDeletes();
            $table->timestamps();
            $table->comment('后台日志');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_logs');
    }
};
