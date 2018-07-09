<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shipping_company_name')->nullable();
            $table->string('shipping_company_tracking_url')->nullable();
            $table->string('location_type')->nullable();
            $table->string('location_name')->nullable();
            $table->float('weight_from')->default(0.0);
            $table->float('weight_to')->default(0.0);
            $table->float('amount')->default(0.0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_rates');
    }
}
