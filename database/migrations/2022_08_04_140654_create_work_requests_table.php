<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Task;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('name')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('contractor_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('address_id')->unsigned()->nullable();
            $table->string('urgency')->nullable();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->bigInteger('store_address_id')->unsigned()->nullable();
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('details')->nullable();
            $table->enum('status', [Task::STATUS_REQUESTED, Task::STATUS_CONFIRMED])->default(Task::STATUS_REQUESTED)->nullable();
            $table->dateTime('lunch_start_time')->nullable();
            $table->dateTime('lunch_end_time')->nullable();
            $table->string('shift')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_requests');
    }
};
