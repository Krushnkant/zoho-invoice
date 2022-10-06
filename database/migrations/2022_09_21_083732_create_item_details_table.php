<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_details', function (Blueprint $table) {
            $table->id();
            $table->integer('invoiceid');
            $table->string('container_no');
            $table->string('seal_no');
            $table->string('container_type');
            $table->string('container_package');
            $table->string('net_weight');
            $table->string('description_goods');
            $table->string('Gross_web');
            $table->string('Measurment');
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
        Schema::dropIfExists('item_details');
    }
}
