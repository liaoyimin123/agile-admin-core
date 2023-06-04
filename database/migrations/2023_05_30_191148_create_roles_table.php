<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('角色名称');
            $table->tinyInteger('status')->default(1)->comment('状态0:禁用1:启用');
            $table->text('auths')->nullable()->comment('权限id集合,json格式');
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('修改时间');
        });
        DB::statement("ALTER TABLE `roles` comment '角色表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
