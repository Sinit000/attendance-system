<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Timetable extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table =  "timetables";
    protected $fillable = [
        'timetable_name',
        'on_duty_time',
        'off_duty_time',
        'late_minute',
        'early_leave',
        'is_reminder',
        'before_timein',
        'before_timeout'
        // 'beginning_end',
        // 'ending_in',
        // 'ending_out',

    ];
    // public function user()
    // {
        
        // return $this->belongsToMany(User::class,'timetable_id','user_id');
        // return $this->belongsToMany(User::class,'timetable_employees','timetable_id','user_id');
    // }
    public function user()
    {
        return $this->hasMany(User::class);
    }
    // public function schedule()
    // {
    //     return $this->hasMany(Schedule::class);
    // }
}
