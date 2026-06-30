<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DispatchItemCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
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
}
