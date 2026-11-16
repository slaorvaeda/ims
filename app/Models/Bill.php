<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_type',
        'invoice_number',
        'invoice_date',
        'party_name',
        'party_gstin',
        'party_address',
        'party_phone',
        'seller_name',
        'seller_gstin',
        'seller_address',
        'seller_phone',
        'buyer_name',
        'buyer_gstin',
        'buyer_address',
        'buyer_phone',
        'subtotal',
        'tax_amount',
        'total_amount',
        'status',
        'payment_mode',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'line_items',
        'raw_extracted_data',
        'notes',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'line_items' => 'array',
            'raw_extracted_data' => 'array',
        ];
    }

    /**
     * Scope for sales bills.
     */
    public function scopeSales($query)
    {
        return $query->where('bill_type', 'sale');
    }

    /**
     * Scope for purchase bills.
     */
    public function scopePurchases($query)
    {
        return $query->where('bill_type', 'purchase');
    }

    /**
     * Get the accessible public URL for the scanned document.
     */
    public function getFileUrlAttribute(): string
    {
        if (empty($this->file_path)) {
            return '';
        }
        return asset('storage/' . $this->file_path);
    }

    /**
     * Format total amount as Indian Rupee.
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return '₹' . number_format((float) ($this->total_amount ?? 0), 2);
    }

    /**
     * Check if document is a PDF.
     */
    public function getIsPdfAttribute(): bool
    {
        $ext = strtolower(pathinfo($this->file_name ?? $this->file_path, PATHINFO_EXTENSION));
        return $ext === 'pdf' || $this->mime_type === 'application/pdf';
    }

    /**
     * Human readable file size.
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $power), 1) . ' ' . ($units[$power] ?? 'B');
    }

    /**
     * Get resolved "FROM" (Seller/Supplier/Issuer) details.
     */
    public function getFromPartyAttribute(): array
    {
        return [
            'name' => $this->seller_name ?: ($this->bill_type === 'purchase' ? $this->party_name : 'Indivolt Appliances & Electricals'),
            'gstin' => $this->seller_gstin ?: ($this->bill_type === 'purchase' ? $this->party_gstin : '06AALFI9222R1ZO'),
            'address' => $this->seller_address ?: ($this->bill_type === 'purchase' ? $this->party_address : 'Sector-43, Basement, 1286P, Gurugram, Haryana'),
            'phone' => $this->seller_phone ?: ($this->bill_type === 'purchase' ? $this->party_phone : null),
        ];
    }

    /**
     * Get resolved "TO" (Buyer/Recipient/Customer) details.
     */
    public function getToPartyAttribute(): array
    {
        return [
            'name' => $this->buyer_name ?: ($this->bill_type === 'sale' ? $this->party_name : 'Indivolt Appliances & Electricals'),
            'gstin' => $this->buyer_gstin ?: ($this->bill_type === 'sale' ? $this->party_gstin : '06AALFI9222R1ZO'),
            'address' => $this->buyer_address ?: ($this->bill_type === 'sale' ? $this->party_address : 'Sector-43, Basement, 1286P, Gurugram, Haryana'),
            'phone' => $this->buyer_phone ?: ($this->bill_type === 'sale' ? $this->party_phone : null),
        ];
    }
}
