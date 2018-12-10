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
        return "{$this->id}_".date('Y-m-d_H:i:s')."_".hash('adler32', $this->id.time());
    }
    
    public static function formatPhoneToNumber($phoneString){
        
        return preg_replace('|\+(7)\s+\((\d+)\)\s+(\d+)-(\d+)-(\d+)|', '$1$2$3$4$5', $phoneString);
        
    }

}
