<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Salary extends Model
{
    use HasFactory, Notifiable,HasApiTokens;
    protected $fillable = [
        'user_id',
        'monthly',
        'base_salary',
        'salary_increment',
        'salary_rate',
        'gross_salary',
        'allowance',
        'ot_rate',
        'ot_hour',
        'ot_method',
        'total_ot',
        'deduction',
        'net_salary',
        'notes',
        'bonus',
        'senority_salary',
        'tax_salary',
        'tax_allowance',
        'advance_salary',
        'currency',
        'exchange_rate'
       
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
