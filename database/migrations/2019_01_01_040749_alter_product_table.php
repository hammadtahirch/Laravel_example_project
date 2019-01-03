<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `global_db`.`products`
                                                    CHANGE COLUMN `is_published` `is_published` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'it will contain 2 status 1) published=true , 2) unpublished=false' , 
                                                    CHANGE COLUMN `published_date` `published_date` TIMESTAMP NULL DEFAULT NULL COMMENT 'it will up to date according to published unpublished status' ,
                                                    CHANGE COLUMN `status` `status` TINYINT(4) NOT NULL DEFAULT 0 COMMENT 'it  will contain multiple status like  1)draft=1 , 2)change_request=2' ;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
