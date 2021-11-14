<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'categories';
    protected $fillable = ['category_name','created_at','updated_at'];

    public $timestamps = false;

    public static function add($category_name)
    {
        $new_category = new self();
        $new_category->category_name = $category_name;
        $new_category->save();
        return $new_category->id;
    }
}
