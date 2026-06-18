<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_name',
        'sku',
        'fsn',
        'asin',
        'updated_by',
    ];

    /**
     * Get the purchase history for the product.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the inward unit serial codes for the product.
     */
    public function inwardItemCodes(): HasMany
    {
        return $this->hasMany(InwardItemCode::class);
    }

    /**
     * Get the sales orders for the product.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the dispatched unit serial codes for the product.
     */
    public function dispatchItemCodes(): HasMany
    {
        return $this->hasMany(DispatchItemCode::class);
    }
}
