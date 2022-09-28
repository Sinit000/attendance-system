<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class TimetableEmployee extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        'timetable_id',
    ];
}
