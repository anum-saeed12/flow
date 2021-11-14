<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportedItem extends Model
{
    use HasFactory;
    protected $table = 'imported_items';
    protected $fillable = ['item_name','category_name','item_description','brand_name','price','weight','height',
        'unit','width','dimension','batch_id'];

}
