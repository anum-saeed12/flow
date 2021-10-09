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
    protected $fillable = ['category_id','item_description','unit','quantity','price','created_at','updated_at'];

    public function category()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

}
