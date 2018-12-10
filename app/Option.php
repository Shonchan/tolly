<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use jdavidbakr\ReplaceableModel\ReplaceableModel;


class Option extends Model
{
    use ReplaceableModel;

    protected $fillable = [
        'product_id', 'feature_id', 'value', 'variant_id'
    ];
}
