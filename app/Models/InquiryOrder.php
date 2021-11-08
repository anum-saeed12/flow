<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class InquiryOrder extends Model
{
    use HasFactory;

    protected $table = 'inquiry_order';
    protected $fillable = ['rate','category_id','item_id','brand_id','quantity','amount','inquiry_id','unit','price','created_at','updated_at'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function items()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function inquiry()
    {
        return $this->hasOne(Inquiry::class, 'id', 'inquiry_id');
    }


}
