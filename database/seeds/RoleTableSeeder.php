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
        DB::statement("
            INSERT INTO `roles` VALUES 
            ('d8402008-3a04-4a7e-b50b-8f33183bbbdc','supper_admin','Supper Admin','Supper Admin','2018-09-17 22:22:02','2018-09-17 22:22:02'),
            ('1893e195-1620-42fb-9eb8-582f3bc973b2','admin','Admin','Admin','2018-09-17 22:22:02','2018-09-17 22:22:02'),
            ('0e87e523-9f85-4861-861a-20d8140cf4a5','moderator','Moderator','Moderator','2018-09-17 22:22:02','2018-09-17 22:22:02'),
            ('8eb82f48-10e9-470a-b041-63d9e702f110','customer','Customer','Customer','2018-09-17 22:22:02','2018-09-17 22:22:02'),
            ('f033dc5d-e11b-4c68-b084-b0725890ba0f','driver','Driver','Driver','2018-09-17 22:22:02','2018-09-17 22:22:02'),
            ('e50b5369-9b68-4a88-b203-9b35e43aa205','shop_keeper','Shop Keeper','Shop Keeper','2018-09-17 22:22:02','2018-09-17 22:22:02');");
    }
}
