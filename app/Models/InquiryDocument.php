<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InquiryDocument extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inquiry_documents';
    protected $fillable = ['inquiry_id','file_path','created_at','updated_at'];

    public function inquiry()
    {
        return $this->hasOne(Inquiry::class, 'id', 'inquiry_id');
    }
}
