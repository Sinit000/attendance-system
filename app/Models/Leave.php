<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Leave extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'reason',
        'note',
        'from_date',
        'to_date',
        'number',
        'date',
        'status',
        'type',
        'subtype_id',
        'leave_deduction',
        'send_status',
        'created_at',
        'updated_at',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function emleave(){
    //     return $this->belongsTo(Leavetype::class);
    // }
    public function leavetype()
    {
        return $this->belongsTo(Leavetype::class,'leave_type_id','id');
    }
}

