<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnquireProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enquire_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->unsignedInteger('product_id')->default(0);
            $table->string('product_code')->nullable();
            $table->unsignedInteger('option_id')->default(0);
            $table->string('option_code')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_image')->nullable();
            $table->string('product_page_url')->nullable();
            $table->string('user_full_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_contact_number')->nullable();
            $table->text('message_body')->nullable();
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
        Schema::dropIfExists('enquire_products');
    }
}
