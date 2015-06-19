<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table)
        {
                $table->increments('id')->comment('主键，自增列');
                $table->string('name')->comment('昵称');
                $table->string('email')->unique()->comment('注册邮箱，用于登陆');
                $table->string('password', 60)->comment('登录密码');
                $table->rememberToken();
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
        Schema::drop('users');
    }
}
