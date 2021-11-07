<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorQuotationItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vendor_quotation_item';
    protected $fillable = ['category_id','item_id','brand_id','vendor_quotation_id','unit','amount','quantity','rate','created_at','updated_at'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }
    public function vendorquotation()
    {
        return $this->hasOne(VendorQuotation::class, 'id', 'vendor_quotation_id');
    }

}
