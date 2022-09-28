<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Overtimecompesation extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        'type',
        'reason',
        'from_date',
        'to_date',
        'duration',
        'date',
        'note',
        'approved_by',
        'status',
        'created_at',
        'updated_at',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
