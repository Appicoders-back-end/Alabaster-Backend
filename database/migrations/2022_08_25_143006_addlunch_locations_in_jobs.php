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
            $table->string('lunch_in_latitude', 20)->nullable();
            $table->string('lunch_in_longitude', 20)->nullable();
            $table->string('lunch_out_latitude', 20)->nullable();
            $table->string('lunch_out_longitude', 20)->nullable();
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
            $table->dropColumn('lunch_in_latitude');
            $table->dropColumn('lunch_in_longitude');
            $table->dropColumn('lunch_in_latitude');
            $table->dropColumn('lunch_out_latitude');
            $table->dropColumn('lunch_out_longitude');
        });
    }
};
