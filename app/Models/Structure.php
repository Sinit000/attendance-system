<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Structure extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'base_salary',
        // 'bonus',
        'allowance',
        // 'currency',
        // 'structure_type_id',
        'name',
    ];
    public function type()
    {
        return $this->belongsTo(Structuretype::class,'structure_type_id','id');
    }
    
    public function contract()
    {
        // return $this->belongsTo(Checkin::class);
        return $this->hasMany(Contract::class,'structure_id','id');
    }
}
