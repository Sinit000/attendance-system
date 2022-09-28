<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Holiday extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'id',
        'name',
        'from_date',
        'to_date',
        'notes',
        'status',
        'duration'

    ];
    public function changeDayoff()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Changedayoff::class,'user_id','id');
    }
}
