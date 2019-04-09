<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name'); // имя для отображения
            $table->unsignedInteger('user_id'); // ссылка на пользователя
            $table->string('server_name'); // полный путь до папки для операций
            $table->boolean('root')->default("0"); // по умолчанию папка не корневая
            $table->unsignedInteger('parent')->nullable();// id родителя У root дирректории не задано
            $table->string('slug')->unique(); // поле uuid 
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
        Schema::dropIfExists('folders');
    }
}
