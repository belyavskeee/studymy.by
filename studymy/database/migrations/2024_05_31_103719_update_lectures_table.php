<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lectures', function (Blueprint $table) {
            // Удаляем столбец time_update
            $table->dropColumn('time_update');

            // Добавляем столбец OKR
            $table->unsignedTinyInteger('OKR')->nullable()->after('homework');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lectures', function (Blueprint $table) {
            // Восстанавливаем столбец time_update
            $table->timestamp('time_update')->useCurrent()->after('teacher_2_id');

            // Удаляем столбец OKR
            $table->dropColumn('OKR');
        });
    }
}

