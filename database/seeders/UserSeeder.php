<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            [
                'name'=>'admin',
                'email'=>'admin@gmail.com',
                'gender'=>"F",
                'username'=>"Admin",
                'password'=>Hash::make('123'),
                'position_id'=>1,
                'department_id'=>1,
                'is_manager'=>"false",
                'role_id' => 1,
                'merital_status'=>"single",
                'spouse_job'=>"0",
                'minor_children'=>"0",
                'timetable_id'=>1,
                'workday_id'=>1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Sinit',
                'email'=>'sinit@gmail.com',
                'gender'=>"F",
                'username'=>"Sinit",
                'password'=>Hash::make('123'),
                'position_id'=>1,
                'department_id'=>1,
                'is_manager'=>"false",
                'role_id' => 2,
                'merital_status'=>"single",
                'spouse_job'=>"",
                'minor_children'=>"",
                'timetable_id'=>1,
                'workday_id'=>1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Idol',
                'email'=>'idol@gmail.com',
                'gender'=>"F",
                'username'=>"Idol",
                'password'=>Hash::make('123'),
                'position_id'=>1,
                'department_id'=>1,
                'is_manager'=>"false",
                'role_id' => 2,
                'merital_status'=>"married",
                'spouse_job'=>"housewife",
                'minor_children'=>"0",
                'timetable_id'=>1,
                'workday_id'=>1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>'Phalla',
                'email'=>'phalla@gmail.com',
                'gender'=>"F",
                'username'=>"Phalla",
                'password'=>Hash::make('123'),
                'position_id'=>1,
                'department_id'=>1,
                'is_manager'=>"false",
                'role_id' => 2,
                'merital_status'=>"married",
                'spouse_job'=>"not housewife",
                'minor_children'=>"1",
                'timetable_id'=>1,
                'workday_id'=>1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],


        ]);
    }
}
