<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "Admin",
            'email' => env('SUPPER_ADMIN_EMAIL'),
            'password' => bcrypt(env('SUPPER_ADMIN_PASSWORD') . "_" . strtolower(env('SUPPER_ADMIN_EMAIL'))),
            'phone_number' => "+1-204-330-5442",
            'role_id' => 1,
            'status' => true,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}
