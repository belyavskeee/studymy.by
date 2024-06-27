<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->string('short_name', 20);
            $table->unsignedBigInteger('teacher1');
            $table->unsignedBigInteger('teacher2')->nullable();
            $table->unsignedBigInteger('spec');
            $table->unsignedBigInteger('group_id');
            $table->integer('hours');
            $table->integer('hours_spent')->default(0);
            $table->timestamps();

            // Добавление внешних ключей и индексов
            $table->foreign('teacher1')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teacher2')->references('id')->on('users')->onDelete('set null');
            $table->foreign('spec')->references('id')->on('specialties')->onDelete('cascade');
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
        Schema::dropIfExists('subjects');
    }
}
