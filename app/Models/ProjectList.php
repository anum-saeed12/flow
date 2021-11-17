<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectList extends Model
{
    use HasFactory;

    protected $table = 'project_lists';

    protected $fillable = [
        'list_id',
        'project_id'
    ];

    public function list ()
    {
        return $this->hasOne(Listicle::class, 'id', 'list_id');
    }
    public function project ()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

}
