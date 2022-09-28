<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Workday extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'working_day',
        'off_day',
        'notes',
        'type_date_time',
        'date_time',
        'is_reminder',
        'remind_from_date'
    ];
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function changeDayoff()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Changedayoff::class,'user_id','id');
    }
    // public function department()
    // {
    //     return $this->hasMany(Department::class);
    // }
}
