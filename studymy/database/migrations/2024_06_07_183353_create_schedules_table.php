<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('number_sub');
            $table->string('time', 10)->nullable();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('lecture_id')->nullable();
            $table->string('type')->nullable();
            $table->string('class_1', 30)->nullable();
            $table->string('class_2', 30)->nullable();
            $table->string('teacher_one', 30)->nullable();
            $table->string('teacher_two', 30)->nullable();
            $table->timestamps();

            // Внешние ключи
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
