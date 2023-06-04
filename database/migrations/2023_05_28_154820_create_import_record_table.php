<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateImportRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_records', function (Blueprint $table) {
            $table->id();
            $table->string('model', 100)->comment('模块名称');
            $table->bigInteger('uid')->comment('用户id');
            $table->uuid('unique_code')->comment('唯一编码');
            $table->string('excel_path')->comment('excel文件路径');
            $table->tinyInteger('status')->default(0)->comment('状态0:待执行1:成功2:失败');
            $table->string('fails', 1000)->nullable()->comment('失败原因');
            $table->timestamp('created_at')->comment('创建时间');
        });
        DB::statement("ALTER TABLE `import_records` comment 'excel导入记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_records');
    }
}
