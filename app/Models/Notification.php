<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
// use Laravel\Passport\HasApiTokens;

class Notification extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'title',
        'body',
        'created_at',
        'date',
        'time',

    ];
}
