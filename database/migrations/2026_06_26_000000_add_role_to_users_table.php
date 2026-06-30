<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('operator')->after('status');
        });

        // Set the first user or admin@example.com to admin role
        $admin = User::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->role = 'admin';
            $admin->save();
        } else {
            $firstUser = User::first();
            if ($firstUser) {
                $firstUser->role = 'admin';
                $firstUser->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
