<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PortalVendor;

class InwardItemCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'portal_vendor_id',
        'uid',
        'quantity',
        'status',
        'mark',
        'updated_by',
    ];

    /**
     * Get the portal associated with the inward item.
     */
    public function portal(): BelongsTo
    {
        return $this->belongsTo(PortalVendor::class, 'portal_vendor_id');
    }

    /**
     * Get the product associated with this item code.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
