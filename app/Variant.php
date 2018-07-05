<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Variant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'sku', 'price', 'compare_price', 'stock', 'external_id'
    ];

    public function product(){
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
