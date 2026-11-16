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
        Schema::table('bills', function (Blueprint $table) {
            $table->string('seller_name')->nullable()->after('party_phone');
            $table->string('seller_gstin')->nullable()->after('seller_name');
            $table->text('seller_address')->nullable()->after('seller_gstin');
            $table->string('seller_phone')->nullable()->after('seller_address');

            $table->string('buyer_name')->nullable()->after('seller_phone');
            $table->string('buyer_gstin')->nullable()->after('buyer_name');
            $table->text('buyer_address')->nullable()->after('buyer_gstin');
            $table->string('buyer_phone')->nullable()->after('buyer_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn([
                'seller_name',
                'seller_gstin',
                'seller_address',
                'seller_phone',
                'buyer_name',
                'buyer_gstin',
                'buyer_address',
                'buyer_phone',
            ]);
        });
    }
};
