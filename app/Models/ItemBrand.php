<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemBrand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'item_brand';
    protected $fillable = ['item_id','brand_id'];

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
