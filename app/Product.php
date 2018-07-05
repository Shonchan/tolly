<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $fillable = [
        'annotation', 'body', 'external_name', 'provider_id', 'image',
    ];

    protected  $variants, $imgs;

    public function __construct( array $attributes = [] )
    {
        parent::__construct( $attributes );

    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'products_categories', 'product_id', 'category_id');
    }

    public function get_variants()
    {

//        return $this->hasMany('App\Variant','product_id', 'id');
        $this->variants =  Variant::where('product_id', '=', $this->id)->get();

        return $this->variants;
    }

    public function variant()
    {
        if($this->variants == null)
            $this->get_variants();
        return $this->variants[0];
    }

    public function img()
    {
        if($this->imgs == null)
            $this->imgs = json_decode($this->images);
        return $this->imgs[0];
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brand_id', 'id');
    }



}
