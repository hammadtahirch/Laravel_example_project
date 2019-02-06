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
            "id"=>"3e6c7a9c-bd42-412d-8bd0-566432b27cda",
            'name' => "Admin",
            'email' => env('SUPPER_ADMIN_EMAIL'),
            'password' => bcrypt(env('SUPPER_ADMIN_PASSWORD') . "_" . strtolower(env('SUPPER_ADMIN_EMAIL'))),
            'phone_number' => "+1-204-330-5442",
            'role_id' => 'd8402008-3a04-4a7e-b50b-8f33183bbbdc',
            'status' => true,
            'created_by' => "d8402008-3a04-4a7e-b50b-8f33183bbbdc",
            'updated_by' => "d8402008-3a04-4a7e-b50b-8f33183bbbdc",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}
