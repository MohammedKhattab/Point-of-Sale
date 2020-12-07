<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Category extends Model implements TranslatableContract
{
    use Translatable;
    public $translatedAttributes = ['name','description'];
    protected $guarded = [];

    public function product(){
        return $this->hasMany(Product::class);
    }
}
