<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Leavetype extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'leave_type',
        'notes',
        'duration',
        'parent_id'
    ];
    public function leave()
    {
       
        return $this->hasMany(Leave::class);
    }
    public function subleavetype()
    {
       
        return $this->hasMany(Subleavetype::class);
    }

}
