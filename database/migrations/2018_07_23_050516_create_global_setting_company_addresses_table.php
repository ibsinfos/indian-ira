<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalSettingCompanyAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_setting_company_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('area')->nullable();
            $table->string('landmark')->nullable();
            $table->string('city')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
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
        Schema::dropIfExists('global_setting_company_addresses');
    }
}
