<?php

use App\Models\Task;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('name')->unsigned()->nullable();
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('contractor_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('cleaner_id')->unsigned()->nullable();
            $table->bigInteger('address_id')->unsigned()->nullable();
            $table->bigInteger('urgency_id')->unsigned()->nullable();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->string('before')->nullable()->comment('image or video');
            $table->string('after')->nullable()->comment('image or video');
            $table->dateTime('date_time')->nullable()->comment('merge date and time');
            $table->date('date')->nullable()->comment('task date');
            $table->time('time')->nullable()->comment('task time');
            $table->text('details')->nullable();
            $table->enum('status', [Task::STATUS_REQUESTED, Task::STATUS_CONFIRMED, Task::STATUS_PENDING, Task::STATUS_WORKING, Task::STATUS_COMPLETED])->default(Task::STATUS_REQUESTED)->nullable();
            $table->dateTime('time_in')->nullable()->comment('task starting time');
            $table->dateTime('time_out')->nullable()->comment('task end time');
            $table->dateTime('break_in')->nullable()->comment('break start time');
            $table->dateTime('break_out')->nullable()->comment('break end time');
            $table->text('note')->nullable();
            $table->text('report_problem')->nullable();
            $table->string('rating', 50)->nullable()->comment('mood reporting/review');
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
        Schema::dropIfExists('tasks');
    }
};
