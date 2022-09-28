<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Checkout extends Model
{

    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'checkout_time',
        'employee_id',
        'date',
        'timetable_id',
        'status',
        'checkout_status',
        'lat',
        'lon',
        'note',
        'checkout_late',
        'location_id',

    ];
    public function employee()
    {
            return $this->belongsTo(Employee::class);
    }
    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }
}
