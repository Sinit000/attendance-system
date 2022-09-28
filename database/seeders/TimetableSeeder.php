<?php

namespace Database\Seeders;

use App\Models\Timetable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TimetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('timetables')->insert([
            [
                'timetable_name'=>"Morning",
                'on_duty_time'=>"08:00:00",
                'off_duty_time'=>"17:00:00",
                'late_minute'=>"0",
                'early_leave'=>"0",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'timetable_name'=>"Morning",
                'on_duty_time'=>"08:00:00",
                'off_duty_time'=>"12:00:00",
                'late_minute'=>"0",
                'early_leave'=>"0",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'timetable_name'=>"Afernoon",
                'on_duty_time'=>"13:00:00",
                'off_duty_time'=>"17:00:00",
                'late_minute'=>"0",
                'early_leave'=>"0",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'timetable_name'=>"Night",
                'on_duty_time'=>"18:00:00",
                'off_duty_time'=>"22:00:00",
                'late_minute'=>"0",
                'early_leave'=>"0",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
