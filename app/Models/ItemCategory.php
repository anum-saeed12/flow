<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'item_categories';
    protected $fillable = ['item_id','brand_id'];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
}
