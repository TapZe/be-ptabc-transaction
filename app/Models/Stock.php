<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Stock extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['stock', 'product_id'];

    /**
     * Get the product associated with the stock
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
