<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagerReviewComments extends Model
{
    protected $fillable = ['manager_id', 'review_id', 'comment'];
    
    public function manager()
    {
        return $this->hasOne('App\User', 'id', 'manager_id');
    }
}
