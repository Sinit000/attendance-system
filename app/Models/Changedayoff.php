<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Changedayoff extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        // 'dayoff_type_id',
        'type',
        'reason',
        'from_date',
        'to_date',
        'duration',
        'date',
        'approved_by',
        'workday_id',
        'holiday_id',
        'status'

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function workday()
    {
        return $this->belongsTo(Workday::class);
    }
    public function holiday()
    {
        return $this->belongsTo(Holiday::class);
    }
}
