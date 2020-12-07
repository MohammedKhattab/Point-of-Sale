<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oreder extends Model
{
    protected $guarded = [];

    public function client(){
        return $this->belongsTo(Client::class );
    }


    public function products(){
        return $this->belongsToMany(Product::class , 'product_order')->withPivot('quantity');
    }


}/* end of model */
