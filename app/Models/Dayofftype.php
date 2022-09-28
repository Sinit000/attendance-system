<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Dayofftype extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    // protected $fillable = [
    //     'name',
    // ];
    // public function dayoff()
    // {
       
    //     return $this->hasMany(Changedayoff::class);
    // }
}
