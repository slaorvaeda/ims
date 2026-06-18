<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'portal_id',
        'product_id',
        'order_date',
        'quantity',
        'updated_by',
    ];

    /**
     * Get the product sold in this order.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
