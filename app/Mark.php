<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    //Get seo_text as array
    public function getSeoAttribute()
    {
        return explode('|', $this->seo_text);
    }
}
