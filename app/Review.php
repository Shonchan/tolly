<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
    
    public function commentManager()
    {
        return $this->hasOne('App\ManagerReviewComments', 'review_id', 'id');
    }
    
    /**
     * считает рейтинг товара, на основе кол-ва отзывов, средней оценки товара 
     * и средней оценки всех товаров данной категории
     * @param int $productId
     * @return double
     */
    public static function getRating($productId){
        $reviews = self::where('product_id', '=', $productId)
                ->selectRaw('sum(rating) / count(id) AS average, count(rating) AS count')
                ->first();
        
        $category = \DB::table('products_categories')
                ->where('product_id', '=', $productId)
                ->value('category_id');
        
        $allReviewsAverage = \DB::table('reviews as r')
                ->selectRaw('sum(r.rating) / count(r.rating) AS average')
                ->leftJoin('products_categories as c', 'c.product_id', '=', 'r.product_id')
                ->where('c.category_id', '=', $category)
                ->value('average');
        
//        \Debugbar::info($allReviewsAverage);
        $rating = $reviews->count / ($reviews->count+1) * $reviews->average + $reviews->count / ($reviews->count+1) * $allReviewsAverage;
        return round($rating, 2);
    }
    
    /**
     * Возвращает кол-во отзывов для продукта
     * @param int $productId
     * @return int
     */
    public static function getCountReviews($productId){
        return self::where('product_id', '=', $productId)
                ->selectRaw('count(id) AS count')
                ->value('count');
    }
    
    /**
     * Считает среднюю оценку на основе всех оценок товара
     * @param int $productId
     * @return double
     */
    public static function getAverageRatingStar($productId){
        $reviews = self::where('product_id', '=', $productId)
                ->selectRaw('sum(rating) / count(rating) AS average')
                ->first();
        
        return ($reviews->average - floor($reviews->average)) >= 0.5 ? round($reviews->average) : floor($reviews->average);
        
    }
    
}
