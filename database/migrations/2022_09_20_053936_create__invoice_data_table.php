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
            $table->text('notify_address');
            $table->string('agent_name');
            $table->text('agent_address'); 
            $table->string('bill_number')->unique();
            $table->string('Bill_of_lading'); 
            $table->string('vessel'); 
            $table->string('voyage');
            $table->string('port_of_loading');
            $table->string('port_of_dischange');
            $table->string('delivery_place');
            $table->string('pre_carriage_by');
            $table->string('port_of_receipt');
            $table->string('contry_origin');
            $table->string('container_no');
            $table->string('container_package');
            $table->string('description_goods');
            $table->string('Gross_web');
            $table->string('Measurment');
            $table->string('freight_charges');
            $table->string('place_of_issue');
            $table->string('place_of_date');
            $table->string('shipped_on_board');
            $table->string('mode_of_shipment');
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
