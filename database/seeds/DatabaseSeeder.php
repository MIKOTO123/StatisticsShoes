<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminCategoryTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
//         $this->call(AdminsTableSeeder::class);
    }
}
