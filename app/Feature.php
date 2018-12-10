<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Feature extends Model
{
    public function foptions(){
        return $this->hasMany('App\Option', 'feature_id', 'id');
    }

    public function getOptionsAttribute()
    {

        return \DB::table('options as o')
                ->join('variants as v', 'o.product_id', '=', 'v.product_id')
                ->join('products as p', 'p.id', '=', 'v.product_id')
                ->where('v.stock', '>', 0)
                ->where('p.enabled', '=', 1)
                ->where('o.feature_id', '=', $this->id)
                ->groupBy('o.value')->get();
    }
}
