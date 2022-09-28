<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Leaveout extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        // 'type',
        'reason',
        'note',
        'time_in',
        'time_out',
        'duration',
        'date',
        'status',
        'check_by',
        'approve_by',
        'type',
        'created_at',
        'updated_at',
        

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
