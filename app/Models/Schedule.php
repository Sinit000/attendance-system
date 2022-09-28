<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Schedule extends Model
{
    use HasFactory, Notifiable,HasApiTokens;
    // protected $table =  "employee_schedule";
    protected $fillable = [
        'employee_id',
        'type',
        'timetable_id',
        'note',
        'working_day',
        'off_day',

    ];
    // public function employee()
    // {
    //     return $this->belongsToMany(Employee::class,'id','employee_id');
    // }
    // public function timetable()
    // {
    //     return $this->belongsToMany(Timetable::class);
    // }


}
