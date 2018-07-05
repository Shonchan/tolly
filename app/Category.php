<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    public function products(){
        return $this->belongsToMany('App\Product', 'products_categories', 'category_id', 'product_id' );
    }

    public function getChilds(){
        return $this->hasMany('App\Category', 'parent_id', 'id');
    }

    public function parent(){
        return $this->belongsTo('App\Category','parent_id', 'id');
    }

    public function childIds(){
        $ids = [];
        $cats = \DB::table('categories')
            ->select('id')
            ->where('parent_id', '=', $this->id)
            ->get('id');
        foreach ( $cats as $cat ) {
            $ids[] = $cat->id;
        }
        return $ids;
    }

    public static function  tree(){
        $cats = Category::orderBy('position', 'asc')->get();

        $categories = array();
        foreach ($cats as $k=>$cat) {
            if($cat->parent_id == 0) {
                $categories[$cat->position] = $cat;
                $categories[$cat->position]->childs = array();
                unset($cats[$k]);
            }
        }

        foreach ( $cats as $cat ) {
            $categories[$cat->parent->position]->childs = array_merge($categories[$cat->parent->position]->childs, array($cat));
        }


        return $categories;
    }
}
