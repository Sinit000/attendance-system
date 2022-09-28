<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
{
    use HasFactory, Notifiable,HasApiTokens;

    protected $table =  "employees";
    protected $fillable = [
        'no',
        'name',
        'gender',
        'nationality',
        'dob',
        'office_tel',
        'card_number',
        'employee_phone',
        'email',
        'profile_url',
        'address',
        'username',
        'password',
        'role',
        'position_id',
        'department_id',
        // 'timetable_id',
        // 'location_id',
        // 'store_id',
        'status',
        'status_leave',
        'created_at',
        'updated_at',
        'check_date',
        // 'working_day','off_day',

    ];


    // protected $hidden = [
    //      'remember_token',
    // ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    // public function timetable()
    // {
    //     return $this->belongsToMany(Timetable::class,'timetable_employees','employee_id','timetable_id');
    //     // return $this->belongsToMany(Timetable::class)->using(Schedule::class);
    // }
    // public function schedule()
    // {
    //     return $this->hasMany(Schedule::class);
    // }
    // public function store()
    // {
    //     return $this->belongsTo(Store::class,'store_id','id');
    // }
    public function checkin()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Checkin::class,'employee_id','id');
    }
    public function leave()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Leave::class,'employee_id','id');
    }
    public function checkout()
    {
        return $this->hasMany(Checkout::class);
    }



    // public function check()
    // {
    //     return $this->hasMany(Check::class);
    // }

    // public function attendance()
    // {
    //     return $this->hasMany(Attendance::class);
    // }
    // public function latetime()
    // {
    //     return $this->hasMany(Latetime::class);
    // }
    // public function leave()
    // {
    //     return $this->hasMany(Leave::class);
    // }
    // public function overtime()
    // {
    //     return $this->hasMany(Overtime::class);
    // }
    protected $hidden = [
        'password', 'remember_token',
    ];




}
