<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listicle extends Model
{
    use HasFactory;

    protected $table = 'listicles';

    protected $fillable = [
        'name',
        'description',
        'sorting_order',
        'created_by',
        'updated_by'
    ];

    public function _created ()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function _updated ()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'list_id', 'id');
    }

}
