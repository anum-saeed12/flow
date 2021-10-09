<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItem extends Model
{

    use HasFactory;
    use SoftDeletes;

    protected $table = 'quotation_item';
    protected $fillable = ['item_id','brand_id','quantity','unit','rate','amount',
        'created_at','updated_at'];

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

}
