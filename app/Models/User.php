<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;



    // public function getRouteKeyName()
    // {
    //     return 'name';
    // }
    protected $table =  "users";
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
        'position_id',
        'department_id',
        'timetable_id',
        'workday_id',
        
        'status',
        'status_leave',
        'created_at',
        'updated_at',
        'check_date',
        'role_id',
        'device_token',
        'is_manager',
        'merital_status',
        'minor_children',
        'spouse_job',
        'is_reminder',
        'remind_from_date',
        'before_timein',
        'before_timeout'
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
  
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    public function timetable()
    {
        // return $this->belongsToMany(Timetable::class,'timetable_employees','user_id','timetable_id');
        return $this->belongsTo(Timetable::class);
    }
    public function workday()
    {
        // return $this->belongsToMany(Timetable::class,'timetable_employees','user_id','timetable_id');
        return $this->belongsTo(Workday::class);
    }
    public function checkin()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Checkin::class,'user_id','id');
    }
    public function leave()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Leave::class,'user_id','id');
    }
    public function leaveout()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Leaveout::class,'user_id','id');
    }
    public function changeDayoff()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Changedayoff::class,'user_id','id');
    }
    public function overtime()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Overtime::class,'user_id','id');
    }
    public function salary()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Salary::class,'user_id','id');
    }
    public function contract()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->belongsTo(Contract::class,'user_id','id');
    }
    public function payslip()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Payslip::class,'user_id','id');
    }
    public function counter()
    {
        
        return $this->hasMany(Counter::class,'user_id','id');
    }
    public function overtimecompesation()
    {
        
        return $this->hasMany(Overtimecompesation::class,'user_id','id');
    }
    
    


    // public function roles()
    // {
    //     return $this->belongsToMany('App\Models\Role', 'role_users', 'user_id', 'role_id');
    // }

    // public function hasAnyRole($roles)
    // {
    //     if (Is_array($roles)) {
    //         foreach ($roles as $role) {
    //             if ($this->hasRole($role)) {
    //                 return true;
    //             }
    //         }
    //     } else {
    //         if ($this->hasRole($roles)) {
    //             return true;
    //         }
    //     }

    //     return false;
    // }

    // public static function hasRole($role)
    // {
    //     if (auth()->user()->roles()->first()->slug === $role) {
    //         return true;
    //     }
    //     return false;
    // }


    // protected $fillable = [
    //     'name', 'email', 'password', 'pin_code',
    // ];


    protected $hidden = [
        'pin_code','password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
