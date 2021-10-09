<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'quotations';
    protected $fillable = ['customer_id','project_name','date','quotation_item_id','discount','terms_condition',
                           'created_at','updated_at'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class, 'id', 'quotation_item_id');
    }
}
