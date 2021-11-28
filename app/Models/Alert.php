<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    protected $table = 'alerts';
    protected $fillable = [
        'message',
        'subject_id',
        'type',
        'user_id',
        'seen'
    ];
}
