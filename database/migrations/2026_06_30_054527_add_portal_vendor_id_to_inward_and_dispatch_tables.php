<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inward_item_codes', function (Blueprint $table) {
            $table->foreignId('portal_vendor_id')->nullable()->constrained('portal_vendors')->nullOnDelete();
        });
        Schema::table('dispatch_item_codes', function (Blueprint $table) {
            $table->foreignId('portal_vendor_id')->nullable()->constrained('portal_vendors')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inward_item_codes', function (Blueprint $table) {
            $table->dropForeign(['portal_vendor_id']);
            $table->dropColumn('portal_vendor_id');
        });
        Schema::table('dispatch_item_codes', function (Blueprint $table) {
            $table->dropForeign(['portal_vendor_id']);
            $table->dropColumn('portal_vendor_id');
        });
    }
};
