<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id'); // ссылка на пользователя
            $table->unsignedInteger('group_id'); // ссылка на пользователя
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
        Schema::dropIfExists('sub_users');
    }
}
