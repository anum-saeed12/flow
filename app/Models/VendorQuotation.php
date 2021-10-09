<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorQuotation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vendor_quotation';
    protected $fillable = ['vendor_id','quotation_ref','vendor_quotation_item_id','quotation_pdf','created_at','updated_at'];

    public function vendors()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }

    public function vendorquotation()
    {
        return $this->hasMany(VendorQuotation::class, 'id', 'vendor_quotation_item_id');
    }


}
