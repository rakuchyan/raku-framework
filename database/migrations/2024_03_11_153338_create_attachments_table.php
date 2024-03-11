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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('file_name', 255)->default('')->comment('文件名');
            $table->string('file_type', 255)->default('')->comment('文件类型');
            $table->integer('file_size')->default(0)->comment('文件大小，单位字节');
            $table->timestamp('upload_time')->comment('上传时间');
            $table->text('path')->comment('文件存储路径');
            $table->timestamps();
            $table->softDeletes();
            $table->comment('附件表');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
};
