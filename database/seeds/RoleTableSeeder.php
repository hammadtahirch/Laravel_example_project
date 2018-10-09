<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Supper Admin",
            'description' => "System Supper Admin",
            'value' => 'supper_admin',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => str_random(10),
            'updated_at' => str_random(10),
        ]);
        DB::table('users')->insert([
            'name' => "Admin",
            'description' => "System Supper Admin",
            'value' => 'admin',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => str_random(10),
            'updated_at' => str_random(10),
        ]);
        DB::table('users')->insert([
            'name' => "Sub Admin",
            'description' => "System Supper Admin",
            'value' => 'sub_admin',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => str_random(10),
            'updated_at' => str_random(10),
        ]);
        DB::table('users')->insert([
            'name' => "Supper Admin",
            'description' => "System Supper Admin",
            'value' => 'supper_admin',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => str_random(10),
            'updated_at' => str_random(10),
        ]);
        DB::table('users')->insert([
            'name' => "Supper Admin",
            'description' => "System Supper Admin",
            'value' => 'supper_admin',
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => str_random(10),
            'updated_at' => str_random(10),
        ]);
    }
}
