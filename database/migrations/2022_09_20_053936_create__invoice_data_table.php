<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_data', function (Blueprint $table) {
            $table->id();
            $table->string('shipper_name');
            $table->text('shipper_address');
            $table->string('consignee_name');
            $table->text('consignee_address');
            $table->text('notify_address')->nullable();
            $table->string('agent_name')->nullable();;
            $table->text('agent_address'); 
            $table->string('bill_number')->unique();
            $table->string('Bill_of_lading'); 
            $table->string('vessel')->nullable();; 
            $table->string('voyage')->nullable();;
            $table->string('port_of_loading')->nullable();;
            $table->string('port_of_dischange')->nullable();;
            $table->string('delivery_place')->nullable();;
            $table->string('pre_carriage_by');
            $table->string('port_of_receipt');
            $table->string('contry_origin');
            $table->string('container_no')->nullable();;
            $table->string('container_package')->nullable();;
            $table->string('description_goods')->nullable();;
            $table->string('Gross_web')->nullable();;
            $table->string('Measurment')->nullable();;
            $table->string('freight_charges');
            $table->string('place_of_issue')->nullable();;
            $table->string('place_of_date')->nullable();;
            $table->string('shipped_on_board')->nullable();;
            $table->string('mode_of_shipment')->nullable();;
            $table->string('freight_payable_at');
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
        Schema::dropIfExists('_invoice_data');
    }
}
