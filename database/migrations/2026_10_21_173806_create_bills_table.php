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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_type')->index(); // 'sale' or 'purchase'
            $table->string('invoice_number')->index();
            $table->date('invoice_date');
            $table->string('party_name')->index(); // Customer/Platform or Vendor/Supplier
            $table->string('party_gstin')->nullable();
            $table->text('party_address')->nullable();
            $table->string('party_phone')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->string('status')->default('Paid'); // Paid, Pending, Partially Paid, Cancelled
            $table->string('payment_mode')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->json('line_items')->nullable();
            $table->json('raw_extracted_data')->nullable();
            $table->text('notes')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
