<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
        'annotation', 'body', 'external_name', 'provider_id', 'image',
    ];

    public function category()
    {
        return $this->belongsToMany('App\Category', 'products_categories', 'product_id', 'id');
    }



}
