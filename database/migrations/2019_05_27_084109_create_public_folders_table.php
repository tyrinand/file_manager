<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_folders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('holder_id'); // ссылка на пользователя владельца группы
            $table->unsignedInteger('group_id'); // группа
            $table->unsignedInteger('root_mount'); // корень монтирования
            $table->unsignedInteger('sub_user'); // подписчик, который передал
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
        Schema::dropIfExists('public_folders');
    }
}
