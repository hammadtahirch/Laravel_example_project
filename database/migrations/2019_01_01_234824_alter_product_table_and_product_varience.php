<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductTableAndProductVarience extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("product_variances", function (Blueprint $table) {
            $table->dropColumn("image");
            $table->dropColumn("price");
            $table->integer("product_id")->after("title");
            $table->integer("min_permitted")->default(0)->after("product_id");
            $table->integer("max_permitted")->default(0)->after("product_id");
        });
        Schema::create("product_variance_option", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shop_id');
            $table->integer('variance_id');
            $table->string('title');
            $table->double('price')->default(0.00);

            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create("nutritional_info", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->integer('option_id');
            $table->integer('option_type')->comment = "it could be option,variance,product";

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
        Schema::table("product_variances",function (Blueprint $table){
            $table->text("image");
            $table->double("price");
            $table->dropColumn("product_id");
            $table->dropColumn("min_permitted");
            $table->dropColumn("max_permitted");
        });
        Schema::drop("product_variance_option");
        Schema::drop("nutritional_info");
    }
}
