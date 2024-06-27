<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilesAndDeviceInfoToErrorsTable extends Migration
{
    public function up()
    {
        Schema::table('errors', function (Blueprint $table) {
            $table->dropColumn('file_1');
            $table->dropColumn('file_2');
            $table->dropColumn('file_3');
            $table->dropColumn('file_4');
            $table->dropColumn('file_5');
            $table->dropColumn('device_info');
            $table->string('device_info_user')->nullable()->after('text');
            $table->json('files')->nullable()->after('device_info_user');
        });
    }

    public function down()
    {
        Schema::table('errors', function (Blueprint $table) {
            $table->string('file_1')->nullable();
            $table->string('file_2')->nullable();
            $table->string('file_3')->nullable();
            $table->string('file_4')->nullable();
            $table->string('file_5')->nullable();
            $table->dropColumn('files');
            $table->dropColumn('device_info_user');
            $table->string('device_info')->nullable();
        });
    }
}
