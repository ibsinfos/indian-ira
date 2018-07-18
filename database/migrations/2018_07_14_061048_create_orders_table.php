<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_code')->nullable();

            $table->unsignedInteger('user_id')->default(0);
            $table->string('user_full_name')->nullable();
            $table->string('user_username')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_contact_number')->nullable();

            $table->unsignedInteger('product_id')->default(0);
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_cart_image')->nullable();
            $table->string('product_page_url')->nullable();
            $table->unsignedInteger('product_number_of_options')->default(0);

            $table->unsignedInteger('product_option_id')->default(0);
            $table->string('product_option_code')->nullable();
            $table->string('product_option_1_heading')->nullable();
            $table->string('product_option_1_value')->nullable();
            $table->string('product_option_2_heading')->nullable();
            $table->string('product_option_2_value')->nullable();
            $table->unsignedInteger('product_stock')->default(0);
            $table->float('product_weight')->default(0.0);
            $table->unsignedInteger('product_quantity')->default(0);

            $table->float('product_selling_price')->default(0.0);
            $table->float('product_discount_price')->default(0.0);

            $table->float('product_net_amount')->default(0.0);
            $table->float('product_gst_amount')->default(0.0);
            $table->float('product_gst_percent')->default(0.0);
            $table->float('product_total_amount')->default(0.0);

            $table->string('payment_method')->nullable(0);

            $table->string('coupon_code')->nullable();
            $table->float('coupon_discount_percent')->default(0.0);

            $table->float('cart_total_net_amount')->default(0.0);
            $table->float('cart_total_gst_amount')->default(0.0);
            $table->float('cart_total_shipping_amount')->default(0.0);
            $table->float('cart_total_cod_amount')->default(0.0);
            $table->float('cart_coupon_amount')->default(0.0);
            $table->float('cart_total_payable_amount')->default(0.0);
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
        Schema::dropIfExists('orders');
    }
}
