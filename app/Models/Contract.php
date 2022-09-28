<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Contract extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        'structure_id',
        'start_date',
        'end_date',
        'working_schedule',
        'status',
        'ref_code'
    ];
    public function user()
    {
        return $this-> belongsTo(User::class);
    }
    public function structure()
    {
        // return $this->belongsToMany(Structure::class,'user_id','id');
        return $this-> belongsTo(Structure::class);
    }
    public function payslip()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Payslip::class,'contract_id','id');
    }
}
