<?php

use App\Models\User;
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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [User::Admin, User::Contractor, User::Cleaner, User::Customer])->after('name');
            $table->string('contact_no', 25)->nullable()->after('password');
            $table->enum('status', [User::Active, User::InActive])->default(User::Active)->after('remember_token');
            $table->string('profile_image', 255)->nullable()->after('contact_no');
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
            $table->dropColumn('role');
            $table->dropColumn('contact_no');
            $table->dropColumn('street');
            $table->dropColumn('state');
            $table->dropColumn('zipcode');
            $table->dropColumn('status');
            $table->dropColumn('profile_image');
        });
    }
};
