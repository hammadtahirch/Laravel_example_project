<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('shop_id');
            $table->string('title');
            $table->text('description');
            $table->text('image');
            $table->double('price')->default(0.00);

            $table->boolean('is_published')->comment('it will contain 2 status 1) published=true , 2) unpublished=false');
            $table->timestamp('published_date')->comment('it will up to date according to published unpublished status');
            $table->tinyInteger('status')->comment('it  will contain multiple status like  1)draft=1 , 2)change_request=2');
            
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
        Schema::dropIfExists('products');
    }
}
