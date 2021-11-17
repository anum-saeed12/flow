<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use HasFactory;

    protected $table = 'task_lists';

    protected $fillable = [
        'list_id',
        'task_id'
    ];

    public function list ()
    {
        return $this->hasOne(Listicle::class, 'id', 'list_id');
    }
    public function task ()
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

}
