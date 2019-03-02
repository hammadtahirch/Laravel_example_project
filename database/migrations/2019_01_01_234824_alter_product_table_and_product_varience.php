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
            $table->uuid("product_id")->after("title");
            $table->integer("min_permitted")->default(0)->after("product_id");
            $table->integer("max_permitted")->default(0)->after("product_id");
        });
        Schema::create("product_variance_options", function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('shop_id');
            $table->uuid('variance_id');
            $table->string('title');
            $table->double('price')->default(0.00);

            $table->uuid('created_by')->default(0);
            $table->uuid('updated_by')->default(0);
            $table->uuid('deleted_by')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create("nutritional_info", function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->uuid('option_id');
            $table->string('option_type')->comment = "it could be option,variance,product";

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
        Schema::table("product_variances",function (Blueprint $table){
            $table->text("image");
            $table->double("price");
            $table->dropColumn("product_id");
            $table->dropColumn("min_permitted");
            $table->dropColumn("max_permitted");
        });
        Schema::drop("product_variance_options");
        Schema::drop("nutritional_info");
    }
}
