<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCollectionTableAndRemoveShopCollection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("collections", function (Blueprint $table) {
            $table->dropColumn("shop_id");
            $table->dropColumn("image");
            $table->uuid("image_id")->nullable()->after("description");
        });

        Schema::drop("shop_collections");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('shop_collections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description');
            $table->text('image');
            $table->uuid('created_by')->default(0);
            $table->uuid('updated_by')->default(0);
            $table->uuid('deleted_by')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table("collections", function (Blueprint $table) {
            $table->uuid("shop_id")->after("id");
            $table->longText("image")->nullable()->after("description");
            $table->dropColumn("image_id");
        });

    }
}
