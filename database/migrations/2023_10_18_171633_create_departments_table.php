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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('')->comment('中文部门名称');
            $table->string('name_en')->default('')->comment('英文部门名称');
            $table->unsignedBigInteger('parent_id')->nullable()->default(0); // 上级部门
            $table->integer('role_id')->default(0)->comment('角色');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('部门表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
};
