<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Position extends Model
{
    use HasFactory, Notifiable,HasApiTokens;
    protected $fillable = [
        'position_name',
        'type',

    ];
    public function employee()
    {
        return $this->hasMany(User::class);
    }
}
