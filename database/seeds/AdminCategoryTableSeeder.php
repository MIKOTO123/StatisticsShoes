<?php

use Illuminate\Database\Seeder;

class AdminCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\App\Models\AdminCategory $adminCategory)
    {
        $adminCategory::create([
            'category_name' => "超级管理员",
            'permission' => "1",
            'mark' => "超级管理员",
        ]);
        //
    }
}
