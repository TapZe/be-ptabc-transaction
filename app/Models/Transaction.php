<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'starting_stock',
        'selled_stock',
        'transaction_date',
        'product_id',
    ];

    /**
     * Get the product that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
