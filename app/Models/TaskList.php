<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use HasFactory;

    protected $table = 'task_lists';

    protected $fillable = [
        'project_id',
        'task_id'
    ];

    public function list ()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
    public function task ()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

}
