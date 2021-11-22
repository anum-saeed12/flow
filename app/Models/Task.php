<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = "tasks";

    protected $fillable = [
        'title',
        'description',
        'points',
        'start_date',
        'end_date',
        'created_by',
        'updated_by'
    ];

    public function created_by ()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function updated_by ()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
