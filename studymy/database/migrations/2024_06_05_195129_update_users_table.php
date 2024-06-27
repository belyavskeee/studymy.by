<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Сделать group_id nullable
            $table->unsignedBigInteger('group_id')->nullable()->change();

            // Удалить столбцы avatar, b_date и num_group
            $table->dropColumn(['avatar', 'b_date', 'num_group']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Вернуть group_id к состоянию non-nullable
            $table->unsignedBigInteger('group_id')->nullable(false)->change();

            // Добавить обратно столбцы avatar, b_date и num_group
            $table->string('avatar', 500)->nullable();
            $table->date('b_date')->nullable();
            $table->string('num_group', 3)->nullable();
        });
    }
}
