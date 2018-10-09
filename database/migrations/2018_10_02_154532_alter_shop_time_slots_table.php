<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShopTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_time_slots', function (Blueprint $table) {
            $table->date('change_delivery_date')->nullable()->change();
            $table->date('change_pickup_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_time_slots', function (Blueprint $table) {
            $table->date('change_delivery_date')->nullable(false)->change();
            $table->date('change_pickup_date')->nullable(false)->change();
        });
    }
}
