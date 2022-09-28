<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Overtime extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        'pay_type',
        'reason',
        'notes',
        'from_date',
        'to_date',
        'date',
        'status',
        'number',
        'type',

        'ot_rate',
        'ot_hour',
        'ot_method',
        'total_ot',
        'pay_status',
        'requested_by',
        'send_status',
        'created_at',
        'updated_at',


    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
