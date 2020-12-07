<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];


    public function getNameAttribute($value){
        return ucfirst($value);
    }


    public function orders(){
        return $this->hasMany(Oreder::class);
    }/* end of orders */

}/* end of Model */
