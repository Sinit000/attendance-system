<?php

namespace Database\Seeders;

use App\Models\Workday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class WorkDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('workdays')->insert([
            [
                'name'=>'standard',
                'working_day'=>"1,2,3,4,5,6",
                'off_day'=>"0",
                'notes'=>"default",
                'type_date_time'=>"server",
                'date_time'=>"",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>'custom',
                'working_day'=>"1,2,3,4,5",
                'off_day'=>"0,6",
                'notes'=>"default",
                'type_date_time'=>"server",
                'date_time'=>"",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Department',
                'working_day'=>"0,2,3,5,6",
                'off_day'=>"1,4",
                'notes'=>"default",
                'type_date_time'=>"server",
                'date_time'=>"",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],

        ]);
    }
}
