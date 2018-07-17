<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->default(0);
            $table->string('order_code')->nullable();

            $table->unsignedInteger('user_id')->default(0);
            $table->string('user_full_name')->nullable();
            $table->string('user_email')->nullable();

            $table->unsignedInteger('product_id')->default(0);
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();

            $table->unsignedInteger('product_option_id')->default(0);
            $table->string('product_option_code')->nullable();

            $table->string('shipping_company')->nullable();
            $table->string('shipping_tracking_url')->nullable();

            $table->string('status')->nullable();

            $table->string('notes')->nullable();
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
        Schema::dropIfExists('order_histories');
    }
}
