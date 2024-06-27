<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marks', function (Blueprint $table) {
            // Изменение существующих столбцов на nullable
            $table->unsignedBigInteger('lecture_id')->nullable()->change();
            $table->integer('hour')->nullable()->change();
            $table->string('mark', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('marks', function (Blueprint $table) {
            // Обратное изменение столбцов на NOT NULL
            $table->unsignedBigInteger('lecture_id')->nullable(false)->change();
            $table->integer('hour')->nullable(false)->change();
            $table->string('mark', 10)->nullable(false)->change();
        });
    }
}
