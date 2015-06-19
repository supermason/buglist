<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bugs', function(Blueprint $table)
        {
            //标题、内容、状态（Pending，Standby，OK）、提交时间、提交人、解决时间、解决方案、解决人、优先级（紧急、一般）、模块、错误号。
            
            $table->increments('id')->comment('主键，自增列');
            $table->string('title')->comment('标题');
            $table->text('bug_img')->nullable()->comment('bug截图');
            $table->text('content')->comment('错误内容');
            $table->string('status')->default(1)->comment('bug状态[Pending，Standby，OK]'); // 新添加时默认为1
            $table->string('presenter_id')->comment('提交人编号');
            $table->datetime('solved_at')->nullable()->comment('解决时间');
            $table->text('solution')->nullable()->comment('解决方案');
            $table->integer('solver_id')->unsigned()->default('0')->comment('解决人编号');
            $table->integer('priority')->unsigned()->default('0')->comment('优先级（紧急、一般）');
            $table->string('model')->nullable()->comment('模块');
            $table->string('error_code')->nullable()->comment('错误号');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bugs');
    }
}
