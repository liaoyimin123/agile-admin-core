<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAuthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auths', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('权限名称');
            $table->bigInteger('pid')->default(0)->comment('上级id');
            $table->tinyInteger('status')->default(1)->comment('状态0:禁用1:启用');
            $table->string('path')->comment('权限路由');
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('修改时间');
        });
        DB::statement("ALTER TABLE `auths` comment '权限表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auths');
    }
}
