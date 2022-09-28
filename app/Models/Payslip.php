<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Payslip extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'user_id',
        // 'contract_id',
        'from_date',
        'to_date',
        // 'base_salary',
        'allowance',
        'bonus',
        // 'total_ot',
        // 'senority_salary',
        'advance_salary',
        'tax_allowance',
        'tax_salary',
        'currency',
        'exchange_rate',
        'deduction',
        'net_salary',
        'notes',
        'gross_salary',
        // 'ot_hour',
        'total_attendance',
        // 'total_leave',
        'wage_hour',
        'net_perday',
        'net_perhour',
        'standance_attendance'
        
        
    ];
    public function user()
    {
        return $this-> belongsTo(User::class);
    }
    public function contract()
    {
        return $this-> belongsTo(Contract::class);
    }
}
