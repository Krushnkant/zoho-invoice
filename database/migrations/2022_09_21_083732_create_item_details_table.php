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
            $table->string('container_no')->nullable();
            $table->string('seal_no')->nullable();
            $table->string('container_type')->nullable();
            $table->string('container_package')->nullable();
            $table->string('net_weight')->nullable();
            $table->string('description_goods')->nullable();
            $table->string('Gross_web')->nullable();
            $table->string('Measurment')->nullable();
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
