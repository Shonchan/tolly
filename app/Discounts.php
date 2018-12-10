<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Discounts extends Model
{

    /**
     * Вычисление скидки
     * @param type $totalPrice
     * @param type $value
     * @param type $type
     * @return type
     */
    public static function calculate($totalPrice, $value, $type){
        
        $result = 0;
        
        switch ($type){
            //рубли
            case 1: 
                $result = $value;
                break;
            //проценты
            case 2: 
                $result = ($totalPrice / 100) * $value;
                break;
        }
        return round($result);
        
    }
    
}
