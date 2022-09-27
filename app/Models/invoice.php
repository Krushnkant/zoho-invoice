<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;
    protected $table = 'invoice_data';

    protected $fillable = [
        'shipper_name',
        'shipper_address',
        'consignee_name',
        'consignee_address',
        'notify_address',
        'agent_name',
        'agent_address',
        'bill_number',
        'Bill_of_lading',
        'vessel',
        'voyage',
        'port_of_loading',
        'port_of_dischange',
        'delivery_place',
        'pre_carriage_by',
        'port_of_receipt',
        'contry_origin',
        // 'container_no',
        // 'container_package',
        // 'description_goods',
        // 'Gross_web',
        // 'Measurment',
        'freight_charges',
        'place_of_issue',
        'place_of_date',
        'shipped_on_board',
        'mode_of_shipment',
        'freight_payable_at',

       
    ];

    public function item()
    {
        return $this->hasMany(item::class,'invoiceid','id');
    }
 }
