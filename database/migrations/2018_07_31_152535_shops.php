<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Shops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->default(0);
            $table->string('title');
            $table->text('description');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('portal_code');
            $table->string('latitude');
            $table->string('longitude');
            $table->uuid('created_by')->default(0);
            $table->uuid('updated_by')->default(0);
            $table->uuid('deleted_by')->default(0);
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
        Schema::dropIfExists('shops');
    }
}
