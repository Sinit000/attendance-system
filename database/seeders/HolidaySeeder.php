<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('holidays')->insert([
            [
                'name'=>"ទិវាចូលឆ្នាំសាកល",
                'from_date'=>"2022-01-01",
                'to_date'=>"2022-01-01",
                'notes'=>'New year Eve',
                'status'=>'pending',
                'duration'=>'1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>"ទិវានារីអន្តរជាតិ",
                'from_date'=>"2022-03-08",
                'to_date'=>"2022-03-08",
                'notes'=>"International Women Right's day",
                'status'=>'pending',
                'duration'=>'1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>"ពិធីបុណ្យចូលឆ្នាំប្រពៃណីជាតិខ្មែរ",
                'from_date'=>"2022-04-14",
                'to_date'=>"2022-04-16",
                'notes'=>"Khmer New's year",
                'status'=>'pending',
                'duration'=>'3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>"ព្រះរាជពិធីបុណ្យចម្រើនព្រះជន្ម ព្រះករុណា ព្រះបាទសម្តេចព្រះបរមនាថ នរោត្តម សីហមុនី ព្រះមហាក្សត្រ នៃព្រះរាជាណាចក្រកម្ពុជា",
                'from_date'=>"2022-05-14",
                'to_date'=>"2022-05-14",
                'notes'=>"King's Birthday",
                'status'=>'pending',
                'duration'=>'1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>"ព្រះរាជពិធីច្រត់ព្រះនង្គ័ល",
                'from_date'=>"2022-05-19",
                'to_date'=>"2022-05-19",
                'notes'=>"Royal Plowing Ceremony",
                'status'=>'pending',
                'duration'=>'1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>"ព្រះរាជពិធីបុណ្យចម្រើនព្រះជន្ម សម្តេចព្រះមហាក្សត្រី នរោត្តម មុនិនាថ សីហនុ ព្រះវររាជមាតាជាតិខ្មែរ ",
                'from_date'=>"2022-06-18",
                'to_date'=>"2022-06-18",
                'notes'=>"Queen Birthday",
                'status'=>'pending',
                'duration'=>'1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>"ពិធីបុណ្យភ្ជុំបិណ្ឌ",
                'from_date'=>"2022-10-24",
                'to_date'=>"2022-10-26",
                'notes'=>"Pchum Ben Ceremony",
                'status'=>'pending',
                'duration'=>'3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>"ព្រះរាជពិធីគ្រងព្រះបរមរាជសម្បត្តិរបស់ ព្រះករុណា ព្រះបាទសម្តេចព្រះបរមនាថ នរោត្តម សីហមុនី ព្រះមហាក្សត្រ នៃព្រះរាជាណាចក្រកម្ពុជា",
                'from_date'=>"2022-10-29",
                'to_date'=>"2022-10-29",
                'notes'=>"King Coronation",
                'status'=>'pending',
                'duration'=>'1',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'=>"ព្រះរាជពិធីបុណ្យអុំទូក បណ្តែតប្រទីប និងសំពះព្រះខែ អកអំបុក",
                'from_date'=>"2022-11-07",
                'to_date'=>"2022-11-09",
                'notes'=>"Water Festival",
                'status'=>'pending',
                'duration'=>'3',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]

        ]);
    }
}
