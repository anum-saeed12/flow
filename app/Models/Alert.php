<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    private $user_id;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->user_id = Auth::id();
    }

    public static function instantiate()
    {
        return (new static());
    }

    public function count()
    {
        $alerts = Alert::select(DB::raw("COUNT(id) as total"))
            ->where('user_id',$this->user_id)
            ->where('seen','0')
            ->first();
        return intval($alerts->total);
    }

    public function fetch($paginate=false,$limit=null)
    {
        $select = [
            "users.username",
            "alerts.id",
            "alerts.message",
            "alerts.action",
            "alerts.subject_id",
            "alerts.type",
            "alerts.seen",
            "alerts.user_id",
            "alerts.created_at",
        ];
        $alerts = Alert::select($select)
            ->join('users','users.id','=','alerts.user_id')
            ->where('alerts.user_id',$this->user_id)
            ->where('alerts.seen','0')
            ->orderBy('alerts.id', 'DESC');
        if (is_null($limit) && !$paginate) $alerts = $alerts->limit($limit);
        if (!$paginate)
            return $alerts->get();
        return $alerts->paginate($paginate);
    }
}
