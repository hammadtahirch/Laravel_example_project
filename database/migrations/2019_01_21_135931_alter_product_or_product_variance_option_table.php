<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductOrProductVarianceOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("products", function (Blueprint $table) {

            $table->integer("price")->default(0)->change();
        });
        Schema::table("product_variance_options", function (Blueprint $table) {

            $table->integer("price")->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!\Doctrine\DBAL\Types\Type::hasType('double')) {
            \Doctrine\DBAL\Types\Type::addType('double', \Doctrine\DBAL\Types\FloatType::class);
        }

        Schema::table("products", function (Blueprint $table) {

            $table->double('price')->default(0.00)->change();
        });
        Schema::table("product_variance_options", function (Blueprint $table) {

            $table->double('price')->default(0.00)->change();
        });
    }
}
