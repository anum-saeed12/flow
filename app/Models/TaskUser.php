<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    use HasFactory;

    protected $table = 'task_users';

    protected $fillable = [
        'task_id',
        'user_id',
        'points',
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
    public function task ()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }
    public function user ()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
