<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->default(0);
            $table->string('order_code')->nullable();

            $table->unsignedInteger('user_id')->default(0);
            $table->string('full_name')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('area')->nullable();
            $table->string('landmark')->nullable();
            $table->string('city')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();

            $table->string('shipping_same_as_billing')->nullable();
            $table->string('shipping_full_name')->nullable();
            $table->string('shipping_address_line_1')->nullable();
            $table->string('shipping_address_line_2')->nullable();
            $table->string('shipping_area')->nullable();
            $table->string('shipping_landmark')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_pin_code')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_country')->nullable();

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
        Schema::dropIfExists('order_addresses');
    }
}
