<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'annotation', 'body', 'external_name', 'provider_id', 'image', 'seo'
    ];

    protected  $variants, $imgs;


    public function __construct( array $attributes = [] )
    {
        parent::__construct( $attributes );

    }

    public static function boot() {
        parent::boot();

        static::deleting(function($product) { // before delete() method call this
            $vids =  \DB::table('variants')->where('product_id','=', $product->id)->pluck('id')->toArray();
            \DB::table('variants')->where('product_id','=', $product->id)->delete();
            \DB::table('products_categories')->where('product_id','=', $product->id)->delete();
            \DB::table('bonuses')->where('product_id','=', $product->id)->delete();
            \DB::table('popularity')->where('product_id','=', $product->id)->delete();
            \DB::table('variant_features')->where('product_id','=', $product->id)->delete();
            \DB::table('options')->where('product_id','=', $product->id)->delete();
            \DB::table('options')->whereIn('variant_id', $vids)->delete();
            \DB::table('rating')->where('product_id','=', $product->id)->delete();
            \DB::table('reviews')->where('product_id','=', $product->id)->delete();

        });
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
        if($this->imgs == null && is_string($this->imgs))
            $this->imgs = json_decode($this->images);
        return $this->imgs[0];
    }

    public function imgSize($width=320, $height=200, $img=''){
        if(empty($img))
            $img = $this->img();

        if(empty($img))
            $img = '';

//        $img = str_replace('.JPEG', '.jpg', $img);

        $resizePath = storage_path('app/public').DIRECTORY_SEPARATOR;
        $parts = explode('.', $img);

        $filename = $parts[0].$width.'x'.$height.'.'.$parts[1];
       // dd($filename);
        if (file_exists($resizePath.$filename))
            return url ('storage', $filename);
        if (file_exists(str_replace('.JPEG', '.jpg',$resizePath.$filename)))
            return url ('storage', str_replace('.JPEG', '.jpg',$filename));
//        dd($resizePath.$img);
        if(file_exists($resizePath.$img)) {

            $image = \Image::make( $resizePath . $img )->fit( $width, $height );
            $image->save( $resizePath . $filename );

        } else {
            return false;
        }
        return url ('storage', $filename);

    }
    
    public function imgSize500($img=''){
        if(empty($img))
            return '';

        $resizePath = storage_path('app/public').DIRECTORY_SEPARATOR;
        $parts = explode('.', $img);

        $filename = $parts[0].'500x500.'.$parts[1];

        if (file_exists($resizePath.$filename))
            return url ('storage', $filename);

        return url ('storage', $img);

    }

    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brand_id', 'id');
    }



}
