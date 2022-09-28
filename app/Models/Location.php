<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Location extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'lat',
        'lon',
        'address_detail',
        'notes'

    ];
    // public function store()
    // {
    //     return $this->belongsTo(Store::class,'store_id','id');
    // }
    public function department()
    {
        return $this->belongsTo(Department::class,'location_id','id');
    }
}
