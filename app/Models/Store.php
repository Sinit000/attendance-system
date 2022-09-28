<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Store extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'store_name',
        'service',
        'note',
        'location_id',

    ];

    // public function employee(){
    //     return $this->hasMany(Employee::class,'employee_id','id');
    // }
    // public function location()
    // {
    //     return $this->belongsTo(Location::class,'location_id','id');
    //     // return $this->hasMany(Location::class,'location_id','id');
    // }

}
