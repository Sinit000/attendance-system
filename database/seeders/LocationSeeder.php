<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('locations')->insert([
            [
                'name'=>"Banban",
                'lat'=>"11.5553092",
                'lon'=>"104.9319072",
                'address_detail'=>"Kabkor",
                'notes'=>'default',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>"PSL Apartment",
                'lat'=>"11.5485511",
                'lon'=>"104.9260475",
                'address_detail'=>"PSL Apartment",
                'notes'=>'default',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}
