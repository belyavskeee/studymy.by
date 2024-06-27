<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('login', 100)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('name', 100);
            $table->string('patronymic', 100);
            $table->string('sname', 100);
            $table->unsignedBigInteger('group_id');
            $table->string('permission', 20);
            $table->date('b_date')->nullable();
            $table->string('num_group', 3)->nullable();
            $table->string('user_password', 100)->nullable();
            $table->string('avatar', 500)->nullable();
            $table->timestamps();

            // Внешний ключ для связи с таблицей групп
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
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
