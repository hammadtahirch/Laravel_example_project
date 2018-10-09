<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopTimeSlots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_time_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shop_id');
            $table->tinyInteger('day');
            
            $table->time('deliver_start_time');
            $table->time('delivery_end_time');
            $table->date('change_delivery_date');
            
            $table->time('pickup_start_time');
            $table->time('pickup_end_time');
            $table->date('change_pickup_date');

            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_time_slots');
    }
}
