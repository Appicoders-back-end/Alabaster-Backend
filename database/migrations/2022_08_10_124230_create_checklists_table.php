<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Checklist;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name', 200)->nullable();
            $table->text('description')->nullable();
            $table->text('attachment')->nullable();
            $table->tinyInteger('is_completed')->default('0');
            $table->enum('status', [Checklist::STATUS_UNASSIGNED, Checklist::STATUS_ASSIGNED])->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('checklists');
    }
};
