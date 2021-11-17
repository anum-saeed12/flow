<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    protected $table = 'task_statuses';

    protected $fillable = [
        'status',
        'task_id'
    ];

    public function task ()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }
}
