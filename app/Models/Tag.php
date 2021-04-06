<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    public $fillable = [
        'name_ar',
        'name_en'
    ];
    //
    public function restaurants(){
        return $this->belongsToMany(\App\Models\Restaurant::class,'restaurant_tag');
    }
}
