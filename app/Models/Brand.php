<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'brands';
    protected $fillable = ['brand_name','attention_person','country','created_at','updated_at'];

    public static function add($brand_name)
    {
        $new_brand = new self();
        $new_brand->brand_name = $brand_name;
        $new_brand->save();
        return $new_brand->id;
    }

}
