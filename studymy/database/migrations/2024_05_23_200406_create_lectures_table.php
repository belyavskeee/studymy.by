<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('timetable_id')->nullable();
            $table->string('type', 50)->nullable();
            $table->unsignedBigInteger('teacher_1_id')->nullable();
            $table->unsignedBigInteger('teacher_2_id')->nullable();
            $table->timestamp('time_update')->useCurrent();
            $table->date('date');
            $table->string('theme', 500)->nullable();
            $table->integer('hours');
            $table->string('homework', 500)->nullable();
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('teacher_1_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('teacher_2_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lectures');
    }
}
