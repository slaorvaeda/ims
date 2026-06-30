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
            $table->json('permissions')->nullable()->after('role');
        });

        // Seed all existing operator users with all permissions by default
        $defaultPermissions = [
            'products',
            'purchases',
            'inward_item_codes',
            'sales',
            'dispatch_item_codes',
            'barcodes'
        ];

        User::where('role', 'operator')->update([
            'permissions' => json_encode($defaultPermissions)
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });
    }
};
