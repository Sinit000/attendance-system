<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StructureSeeder extends Seeder
{
   
    public function run()
    {
        DB::table('structures')->insert([
            [
                'name' => 'HR',
                'base_salary' => '500',
                'allowance' => '100',
                // 'structure_type_id' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cheif Department',
                'base_salary' => '400',
                'allowance' => '100',
                // 'structure_type_id' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Office',
                'base_salary' => '300',
                'allowance' => '0',
                // 'structure_type_id' => '1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
