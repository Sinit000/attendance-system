<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Checkin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'checkin_time',
        'checkout_time',
        'user_id',
        'date',
        // 'lat',
        // 'lon',
        'duration',
        'status',
        'checkin_status',
        'checkout_status',
        'checkout_late',
        'checkin_late',
        'duration',
        'note',
        'created_at',
        'updated_at',
        'send_status',
        'confirm',
        'ot_status',
        'payslip_status'

    ];
    // public function timetable()
    // {
    //     return $this->belongsTo(Timetable::class);
    // }

    public function user()
    {
        return $this-> belongsTo(User::class);
    }
}
