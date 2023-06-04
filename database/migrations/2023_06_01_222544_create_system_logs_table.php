<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSystemLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('uid')->comment('用户id');
            $table->string('method', 50)->comment('请求方式');
            $table->string('path')->comment('路由地址');
            $table->string('params', 500)->comment('请求参数');
            $table->string('ip', 100)->comment('ip地址');
            $table->timestamp('created_at')->comment('创建时间');
        });
        DB::statement("ALTER TABLE `system_logs` comment '系统日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_logs');
    }
}
