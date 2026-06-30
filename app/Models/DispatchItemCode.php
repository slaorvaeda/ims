<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PortalVendor;

class DispatchItemCode extends Model
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
     * Get the product associated with this dispatch item code.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the portal associated with the dispatch item.
     */
    public function portal(): BelongsTo
    {
        return $this->belongsTo(PortalVendor::class, 'portal_vendor_id');
    }
}
