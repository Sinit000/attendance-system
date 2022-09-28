<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            [
                'department_name'=>"IT Office",
                // 'workday_id'=>1,
                'location_id'=>1,
                'notes'=>'default',
               
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'department_name'=>"Security",
                // 'workday_id'=>1,
                'location_id'=>1,
                'notes'=>'default',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]
        ]);
        // Department::create(
        //     [
        //     'department_name'=>"IT Office",
        //     'workday_id'=>1,
        //     'location_id'=>1,
        //     'notes'=>'default'
        //     ]
        // );
    }
}
