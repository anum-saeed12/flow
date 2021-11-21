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
        'created_by',
        'updated_by'
    ];

    public function created ()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function updated ()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

}
