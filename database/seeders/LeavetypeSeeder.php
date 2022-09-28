<?php

namespace Database\Seeders;

use App\Models\Leavetype;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LeavetypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('leavetypes')->insert([
            [
                'leave_type'=>"Sick Leave",
                'notes'=>'Deduction salary',
                'duration'=>"0",
                'parent_id'=>0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'leave_type'=>"Maternity Leave",
                'notes'=>'standard',
                'duration'=>"1_month",
                'parent_id'=>0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'leave_type'=>"Hospitality Leave",
                'notes'=>'Not duduction salary',
                'duration'=>"7",
                'parent_id'=>0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            
            [
                'leave_type'=>"Special Leave",
                'notes'=>'Not deduction salary',  
                'duration'=>"0",
                'parent_id'=>0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'leave_type'=>"Unpaid Leave",
                'notes'=>'Duduction salary',
                'duration'=>"0",
                'parent_id'=>0,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'leave_type'=>"Marriage Leave",
                'notes'=>'Sub leavetype of special leave',
                'duration'=>"3",
                'parent_id'=>4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'leave_type'=>"Peternity Leave",
                'notes'=>'Sub leavetype of special leave',
                'duration'=>"7",
                'parent_id'=>4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'leave_type'=>"Funeral Leave",
                'notes'=>'Sub leavetype of special leave',
                'duration'=>"3",
                'parent_id'=>4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ]);
        // Leavetype::create(
        //     [
        //         'leave_type'=>"Permission",
        //         'notes'=>'standard',
        //         'scope'=>"0"
        //     ],
        //     [
        //         'leave_type'=>"Travel",
        //         'notes'=>'standard',
        //         'scope'=>"0"
        //     ],
        // );
    }
}
