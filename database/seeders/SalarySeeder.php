<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('salaries')->insert([
        //     [
        //         'user_id'=>2,
        //         'monthly'=>"June",
        //         'base_salary'=>"250",
        //         'salary_increment'=>"50",
        //         'salary_rate'=>"5",
        //         'gross_salary'=>"300",
        //         'allowance'=>"10",
        //         'ot_rate'=>"5",
        //         'ot_hour'=>"10",
        //         'ot_method'=>"2",
        //         'total_ot'=>"100",
        //         'deduction'=>"5",
        //         'net_salary'=>"405",
                
               
        //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //         'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     ],
        //     [
                
        //         'user_id'=>3,
        //         'monthly'=>"June",
        //         'base_salary'=>"250",
        //         'salary_increment'=>"0",
        //         'salary_rate'=>"5",
        //         'gross_salary'=>"250",
        //         'allowance'=>"10",
        //         'ot_rate'=>"0",
        //         'ot_hour'=>"0",
        //         'ot_method'=>"0",
        //         'total_ot'=>"0",
        //         'deduction'=>"5",
        //         'net_salary'=>"255",
        //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //         'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     ],
        //     [
        //         'user_id'=>4,
        //         'monthly'=>"June",
        //         'base_salary'=>"300",
        //         'salary_increment'=>"0",
        //         'salary_rate'=>"5",
        //         'gross_salary'=>"300",
        //         'allowance'=>"0",
        //         'ot_rate'=>"5",
        //         'ot_hour'=>"5",
        //         'ot_method'=>"1.5",
        //         'total_ot'=>"37.5",
        //         'deduction'=>"5",
        //         'net_salary'=>"332.5",
                
               
        //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //         'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     ],
            
        // ]);
    }
}
