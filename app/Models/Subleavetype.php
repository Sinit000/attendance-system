<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Subleavetype extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'duration',
        'leave_type_id'
    ];
    public function leavetype()
    {
        return $this->belongsTo(Leavetype::class,'leave_type_id','id');
    }
}
