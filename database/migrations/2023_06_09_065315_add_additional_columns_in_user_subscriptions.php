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
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->after('payment_method_id', function ($after) {
                $after->string('stripe_charge_id')->nullable();
                $after->date('start_date')->nullable();
                $after->date('end_date')->nullable();
                $after->tinyInteger('is_expired')->default('0')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropColumn('stripe_charge_id');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('is_expired');
        });
    }
};
