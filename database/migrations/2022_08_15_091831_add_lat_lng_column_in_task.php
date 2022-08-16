<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('time_in_latitude', 20)->nullable();
            $table->string('time_in_longitude', 20)->nullable();
            $table->string('before_lunch_attachment')->nullable();
            $table->string('after_lunch_attachment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('time_in_latitude');
            $table->dropColumn('time_in_longitude');
            $table->dropColumn('before_lunch_attachment');
            $table->dropColumn('after_lunch_attachment');
        });
    }
};
