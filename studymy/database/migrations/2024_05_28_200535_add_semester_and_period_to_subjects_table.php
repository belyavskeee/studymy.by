<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSemesterAndPeriodToSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->tinyInteger('semester')->after('group_id')->nullable(); // 1 или 2
            $table->string('period_start', 2)->after('semester')->nullable(); // '21'
            $table->string('period_end', 2)->after('period_start')->nullable(); // '22'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('semester');
            $table->dropColumn('period_start');
            $table->dropColumn('period_end');
        });
    }
}
