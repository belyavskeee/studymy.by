<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyGroupsTableNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Изменение уже существующих колонок, чтобы сделать их nullable
            $table->string('audience', 10)->nullable()->change();
            $table->string('type_education', 15)->nullable()->change();
            $table->integer('after_school')->nullable()->change();
            $table->date('year_z')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Возвращение колонок в состояние nullable(false)
            $table->string('audience', 10)->nullable(false)->change();
            $table->string('type_education', 15)->nullable(false)->change();
            $table->integer('after_school')->nullable(false)->change();
            $table->date('year_z')->nullable(false)->change();
        });
    }
}
