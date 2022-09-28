<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Department extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'department_name',
        // 'workday_id',
        'manager',
        'location_id',
        'notes'
    ];
    public function employee()
    {
        return $this->hasMany(User::class);
    }
    // public function workday()
    // {
    //     return $this->belongsTo(Workday::class);
    //     // return $this->hasMany(Location::class,'location_id','id');
    // }
    // public function group(){
    //     return $this->belongsTo(GroupDepartment::class,'group_department_id','id');
    // }
    public function location()
    {
        return $this->belongsTo(Location::class);
        // return $this->hasMany(Location::class,'location_id','id');
    }
}
