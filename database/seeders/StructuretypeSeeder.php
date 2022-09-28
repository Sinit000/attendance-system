<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StructuretypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('structuretypes')->insert([
        //     [
        //         'name' => 'HR',
        //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //         'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     ],
        //     [
        //         'name' => 'Cheif Department',
        //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //         'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     ],
        //     [
        //         'name' => 'Office',
        //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //         'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //     ],
        // ]);
    }
}
