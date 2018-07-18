<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPriceAndOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_price_and_options', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->default(0);
            $table->string('option_code');
            $table->string('option_1_heading')->nullable();
            $table->string('option_1_value')->nullable();
            $table->string('option_2_heading')->nullable();
            $table->string('option_2_value')->nullable();
            $table->float('selling_price')->default(0.0);
            $table->float('discount_price')->default(0.0);
            $table->unsignedInteger('stock')->default(0);
            $table->float('weight')->default(0.0);
            $table->string('display')->nullable();
            $table->string('image')->nullable();
            $table->string('gallery_image_1')->nullable();
            $table->string('gallery_image_2')->nullable();
            $table->string('gallery_image_3')->nullable();
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
        Schema::dropIfExists('product_price_and_options');
    }
}
