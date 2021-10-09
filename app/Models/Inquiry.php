<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inquiries';
    protected $fillable = ['customer_id','project_name','date','timeline','inquiry_order_id','files','remarks','created_at','updated_at'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function order()
    {
        return $this->hasMany(InquiryOrder::class, 'id', 'inquiry_order_id');
    }
}
