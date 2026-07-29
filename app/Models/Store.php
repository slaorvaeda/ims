<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_name',
        'portal_vendor_id',
        'status',
        'credentials',
        'enabled_apis',
    ];

    /**
     * Cast attributes.
     * We encrypt credentials to prevent API tokens/secrets from being stored in plain text.
     */
    protected $casts = [
        'credentials' => 'encrypted:array',
        'enabled_apis' => 'array',
    ];

    /**
     * Relationship: A store belongs to a portal vendor.
     */
    public function portalVendor(): BelongsTo
    {
        return $this->belongsTo(PortalVendor::class, 'portal_vendor_id');
    }
}
