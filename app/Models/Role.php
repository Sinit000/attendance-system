<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;


class Role extends Model
{
    use HasFactory, Notifiable,HasApiTokens;
    protected $fillable = [
        'name'
    ];
    public function user()
    {
        return $this-> hasMany(User::class);
    }
}
