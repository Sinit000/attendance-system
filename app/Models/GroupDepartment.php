<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class GroupDepartment extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'notes',
        // 'workday_id',
    ];
    // public function department()
    // {
    //     return $this->hasMany(Department::class,'group_department_id','id');
    // }
    // public function workday()
    // {
    //     return $this->belongsTo(Workday::class,'workday_id','id');
    //     // return $this->hasMany(Location::class,'location_id','id');
    // }
}
