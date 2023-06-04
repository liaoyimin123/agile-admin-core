<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
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
            $table->string('avatar')->nullable()->comment('用户头像');
            $table->string('username')->comment('用户名');
            $table->string('password')->comment('密码');
            $table->tinyInteger('status')->default(1)->comment('状态1:启用,0:禁用');
            $table->text('roles')->nullable()->comment('角色id集合,json格式');
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('修改时间');
        });
        DB::statement("ALTER TABLE `users` comment '用户表'");
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
}
