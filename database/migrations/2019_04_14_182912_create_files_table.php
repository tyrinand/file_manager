<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedInteger('user_id'); // ссылка на пользователя
            $table->string('user_name'); // имя для отображения
            $table->unsignedInteger('size'); // размер в md
            $table->string('server_name'); // шифрованное имя 
            $table->string('server_path'); // полный путь до файла включая имя
            $table->unsignedInteger('parent');// id родительской папки
            $table->string('slug')->unique(); // для генерации ссылки
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
