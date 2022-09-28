<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Counter extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        'ot_duration',
        'total_ph',
        'hospitality_leave',
        'marriage_leave',
        'peternity_leave',
        'funeral_leave',
        'maternity_leave'

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
