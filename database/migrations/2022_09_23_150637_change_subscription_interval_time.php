<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
//            $table->enum('interval_time', ['week', 'month', 'year'])->nullable()->change();
            DB::statement("ALTER TABLE subscriptions CHANGE COLUMN interval_time interval_time ENUM('week', 'month', 'year') NOT NULL DEFAULT 'month'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
        });
    }
};
