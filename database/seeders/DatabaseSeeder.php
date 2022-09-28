<?php

namespace Database\Seeders;

use App\Models\GroupDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(RoleSeeder::class);
        $this->call(WorkDaySeeder::class);
        $this->call(LocationSeeder::class);
        // $this->call(GroupDepartmentSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(HolidaySeeder::class);
        $this->call(TimetableSeeder::class);
        $this->call(LeavetypeSeeder::class);
        // $this->call(SubleavetypeSeeder::class);
       
        $this->call(UserSeeder::class);
        // $this->call(StructuretypeSeeder::class);
       
        $this->call(StructureSeeder::class);
        // $this->call(DayofftypeSeeder::class);
       
        //

        // if (Schema::hasTable('roles')) {
        //     if (DB::table('roles')->count() > 0) {
        //         DB::table('roles')->truncate();
        //     }

        //     DB::table('roles')->insert([
        //         [
        //             'name' => 'Admin',
        //         ],
        //         [
        //             'name' => 'Staff',
        //         ],
        //     ]);
        // }

        // if (Schema::hasTable('users')) {
        //     if (DB::table('users')->count() > 0) {
        //         DB::table('users')->truncate();
        //     }

        //     DB::table('users')->insert([
        //         [
        //             'nip' => null,
        //             'name' => 'Admin',
        //             'email' => 'admin@gmail.com',
        //             'password' => bcrypt('123'),
        //             'profile_url' => 'admin.jpg',
        //             'role_id' => 1,
        //             'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //         ],
        //     ]);
        // }

    }
}
