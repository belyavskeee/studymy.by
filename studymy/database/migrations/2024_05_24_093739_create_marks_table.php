<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id')->index(); // Содержит id предмета
            $table->unsignedBigInteger('lecture_id')->index(); // Содержит id лекции
            $table->unsignedBigInteger('teacher_id'); // Содержит id преподавателя
            $table->unsignedBigInteger('student_id'); // Содержит id студента
            $table->string('type', 50); // Содержит тип отметки
            $table->integer('hour'); // Содержит информацию на какой половине пары стоит отметка
            $table->timestamp('time_input'); // Содержит дату и время отметки
            $table->string('mark', 10); // Содержит отметку
            $table->timestamps();

            // Внешние ключи
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('lecture_id')->references('id')->on('lectures')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marks');
    }
}
