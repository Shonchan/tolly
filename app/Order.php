<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    protected $fillable = [];

    /**
     * Генерация уникальнго id для оплаты
     * @return type
     */
    public function generateEcApiId(){
        return "{$this->id}_".date('d.m.Y_H:i:s');
    }
    
    public static function formatPhoneToNumber($phoneString){
        
        return str_replace(['+', '(', ')', '-', '_', ' '], '', $phoneString);
        
    }

}
