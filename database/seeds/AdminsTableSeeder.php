<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(\App\Models\Admin $admin)
    {
        $admin::create([
            'name' => 'admin',
            'password' => 'abc1233',
            'category_id' => 1,//填充id不好指定?
            'nickname' => "超级管理员",
        ]);
    }
}
