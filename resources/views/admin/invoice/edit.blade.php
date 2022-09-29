

        

<div class="row">
  <div class="col-md-8">

    <div id="page-wrap" class="table-textarea">
    <form class="row g-3 needs-validation" id="invoiceForm" novalidate>
    {{ csrf_field() }}

    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
    <div class="col-md-6">
      <div class="form-group">
        <label for="sname" class="form-label">Shipper Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="sname" value="{{ $invoice->shipper_name }}" id="sname" value="" required>
        <label id="sname-error" class="error invalid-feedback animated fadeInDown" for="sname"></label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label for="validationCustom02" class="form-label">Consignee Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="cname" value="{{ $invoice->consignee_name }}" id="validationCustom02" value="" required>
        <label id="cname-error" class="error invalid-feedback animated fadeInDown" for="cname"></label>
      </div>
    </div>



  <div class="col-md-6 mt-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Shipper Address <span class="text-danger">*</span></label>

    <textarea rows="4"  class="form-control" cols="50"  id="validationCustom03" name="saddress" required>{{ $invoice->shipper_address	 }}
</textarea>
<label id="saddress-error" class="error invalid-feedback animated fadeInDown" for="saddress"></label>
  </div>
</div>
  <div class="col-md-6 mt-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Consignee Address <span class="text-danger">*</span></label>

    <textarea rows="4"  class="form-control" cols="50" id="validationCustom03" name="caddress" required>{{ $invoice->consignee_address }}
</textarea>
<label id="caddress-error" class="error invalid-feedback animated fadeInDown" for="caddress"></label>
  </div>
</div>
  <div class="col-md-12 mt-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Notify Address <span class="text-danger">*</span></label>

    <textarea rows="4"  class="form-control" cols="50"   id="validationCustom03" name="naddress" required>{{ $invoice->notify_address }}
</textarea>
<label id="naddress-error" class="error invalid-feedback animated fadeInDown" for="naddress"></label>
  </div>
</div>

 
 
 
  <div class="col-md-12  mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Pre carriage by</label>
    <input type="text" class="form-control" name ="pcarriageby" value="{{ $invoice->pre_carriage_by }} " id="validationCustom05" required>
    <label id="pcarriageby-error" class="error invalid-feedback animated fadeInDown" for="pcarriageby"></label>
</div>
  </div>
  <div class="col-md-6  mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Pier or port of receipt</label>
    <input type="text" class="form-control" name= "por" value="{{ $invoice->port_of_receipt }} " id="validationCustom05" required>
    <label id="por-error" class="error invalid-feedback animated fadeInDown" for="por"></label>
    </div>
  </div>
 
  <div class="col-md-6  mt-2">
    <div class="form-group">
    <label for="validationCustom05" class="form-label">Country of Origin</label>
    <input type="text" class="form-control" name="cor" value=" {{ $invoice->contry_origin }}" id="validationCustom05" required>
    <label id="cor-error" class="error invalid-feedback animated fadeInDown" for="cor"></label>
    </div>
  </div>

  <div class="col-md-3 mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Countainer no</label>
    <input type="text" class="form-control" name="containerno[]" value="{{ $invoice->item[0]->container_no }} "id="validationCustom05" required>
    <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
  </div>
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value="{{ $invoice->item[0]->Gross_web }}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-3 mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">No of countainer package</label>
    <input type="text" class="form-control" name="countainerpackage[]" value="{{ $invoice->item[0]->container_package }}  " id="validationCustom05" required>
    <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
  </div>
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{ $invoice->item[0]->Measurment }} " id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>
  <div class="col-md-6 mt-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Description of goods</label>

    <textarea rows="5"  class="form-control" name="description[]"  cols="50" id="validationCustom03" name="comment" required>{{ $invoice->item[0]->description_goods }} 
</textarea>
<label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
    </div>
  </div>
  <!-- <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value="{{ $invoice->item[0]->Gross_web }}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{ $invoice->item[0]->Measurment }} " id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>  -->

  <div class="col-md-3 mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Countainer no</label>
    <input type="text" class="form-control" name="containerno[]" value="{{  $invoice->item[1]->container_no  }} "id="validationCustom05" required>
    <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
  </div>
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value=" {{  $invoice->item[1]->Gross_web  }}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-3 mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">No of countainer package</label>
    <input type="text" class="form-control" name="countainerpackage[]" value="{{  $invoice->item[1]->container_package  }} " id="validationCustom05" required>
    <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
    </div>
    <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{  $invoice->item[1]->Measurment  }} " id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>
  <div class="col-md-6 mt-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Description of goods</label>

    <textarea rows="5"  class="form-control" name="description[]"  cols="50" id="validationCustom03" name="comment" required>{{$invoice->item[1]->description_goods}}
</textarea>
    <label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
    </div>
  </div>
  <!-- <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value=" {{  $invoice->item[1]->Gross_web  }}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{  $invoice->item[1]->Measurment  }} " id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>  -->

  <div class="col-md-3  mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Countainer no</label>
    <input type="text" class="form-control" name="containerno[]" value="{{  $invoice->item[2]->container_no  }} "id="validationCustom05" required>
    <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
  </div>
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value=" {{  $invoice->item[2]->Gross_web  }}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-3  mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">No of countainer package</label>
    <input type="text" class="form-control" name="countainerpackage[]" value="{{  $invoice->item[2]->container_package }}" id="validationCustom05" required>
    <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
    </div>
    <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{  $invoice->item[2]->Measurment  }} " id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>
  <div class="col-md-6  mt-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Description of goods</label>

    <textarea rows="5"  class="form-control" name="description[]"  cols="50" id="validationCustom03" name="comment" required>{{  $invoice->item[2]->description_goods }}
</textarea>
    <label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
    </div>
  </div>
  <!-- <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value=" {{  $invoice->item[2]->Gross_web  }}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{  $invoice->item[2]->Measurment  }} " id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>  -->

  <div class="col-md-3 mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Countainer no</label>
    <input type="text" class="form-control" name="containerno[]" value="{{  $invoice->item[3]->container_no  }} "id="validationCustom05" required>
    <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
  </div>
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value=" {{ $invoice->item[3]->Gross_web}}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-3  mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">No of countainer package</label>
    <input type="text" class="form-control" name="countainerpackage[]" value="{{  $invoice->item[3]->container_package  }}" id="validationCustom05" required>
    <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
    </div>
    <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{$invoice->item[3]->Measurment }} " id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>
  <div class="col-md-6  mt-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Description of goods</label>

    <textarea rows="5"  class="form-control" name="description[]"  cols="50" id="validationCustom03" name="comment" required>{{  $invoice->item[3]->description_goods }}
</textarea>
<label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
    </div>
  </div>
  <!-- <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value=" {{ $invoice->item[3]->Gross_web}}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{$invoice->item[3]->Measurment }} " id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>  -->

  <div class="col-md-3  mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Countainer no</label>
    <input type="text" class="form-control" name="containerno[]" value="{{   $invoice->item[4]->container_no  }} "id="validationCustom05" required>
    <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
  </div>
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value=" {{ $invoice->item[4]->Gross_web}}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-3  mt-2">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">No of countainer package</label>
    <input type="text" class="form-control" name="countainerpackage[]" value="{{  $invoice->item[4]->container_package }} " id="validationCustom05" required>
    <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
    </div>
    <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{$invoice->item[4]->Measurment }}" id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>
  <div class="col-md-6  mt-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Description of goods</label>

    <textarea rows="5"  class="form-control" name="description[]"  cols="50" id="validationCustom03" name="comment" required>{{  $invoice->item[4]->description_goods }}
</textarea>
<label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
    </div>
  </div>
  <!-- <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Gross Webeight</label>

    <input type="text" class="form-control" name="gross[]" value=" {{ $invoice->item[4]->Gross_web}}" id="validationCustom05" required>
    <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
    </div>
  </div>
  <div class="col-md-2">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Measurment</label>

    <input type="text" class="form-control" name="mesurment[]" value="{{$invoice->item[4]->Measurment }}" id="validationCustom05" required>
    <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
    </div>
  </div>  -->
 
 
        <button type="button" class="btn btn-primary mt-3" id="invoice_submit" name="invoice_submit" action="update">Save <i class="fa fa-circle-o-notch fa-spin loadericonfa" style="display:none;"></i></button>
    </div>
</div>
<div class="col-md-4">
<div class="col-md-12">
  <center><h3>For Admin</h3></center>
      <div class="form-group">
        <label for="validationCustomUsername" class="form-label">Agent Name <span class="text-danger">*</span></label>
        <div class="input-group has-validation">
          <input type="text" class="form-control" name="aname" value="{{ $invoice->agent_name }} " id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
          <label id="aname-error" class="error invalid-feedback animated fadeInDown" for="aname"></label>
        </div>
      </div>
    </div>
    <div class="col-md-12 mt-4">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Agent Address <span class="text-danger">*</span></label>

    <textarea rows="4"  class="form-control" cols="50"  id="validationCustom03" name="agaddress" required>{{ $invoice->agent_address }}
    </textarea>
<label id="agaddress-error" class="error invalid-feedback animated fadeInDown" for="agaddress"></label>
  </div>
</div>
<div class="col-md-12 mt-4">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Bill Of Lading <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="blading" value=" {{ $invoice->Bill_of_lading }}"id="validationCustom05" required>
    <label id="blading-error" class="error invalid-feedback animated fadeInDown" for="blading"></label>
  </div>
  </div>
  <div class="col-md-12 mt-4">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Place of date <span class="text-danger">*</span></label>

    <input type="date" class="form-control" name="pdate" value="{{ date('Y-m-d',strtotime($invoice->place_of_date)) }}" id="validationCustom05" required>
    <label id="pdate-error" class="error invalid-feedback animated fadeInDown" for="freight"></label>
    </div>
  </div>
  <div class="col-md-12 mt-4">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Place of issue <span class="text-danger">*</span></label>

    <input type="text" class="form-control" name="place" value="{{ $invoice->place_of_issue }} " id="validationCustom05" required>
    <label id="place-error" class="error invalid-feedback animated fadeInDown" for="place"></label>
    </div>
  </div>
  <div class="col-md-12 mt-4">
  <!-- <div class="form-group">
    <label for="validationCustom03" class="form-label">Freight & charges<span class="text-danger">*</span></label>

    <input type="text" class="form-control" name="freight" value="{{ $invoice->freight_charges }} " id="validationCustom05" required>
    <label id="freight-error" class="error invalid-feedback animated fadeInDown" for="freight"></label>
    </div> -->
    <div class="form-group">
                <label for="validationCustom03" class="form-label">Freight & charges <span class="text-danger">*</span></label>

                <select class="form-select form-control" name="freight" id="validationCustom04" required>
                  <option disabled value="">Choose...</option>
                  <option value="prepaid" {{$invoice->freight_charges == "prepaid" ? "Selected" : ""}}>Prepaid</option>
                  <option value="collect"{{$invoice->freight_charges == "collect" ? "Selected" : ""}}>Collect</option>
                </select>
                <label id="freight-error" class="error invalid-feedback animated fadeInDown" for="freight"></label>
              </div>
  </div>

  <div class="col-md-12 mt-4">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Freight_payble_at <span class="text-danger">*</span> </label>

    <input type="text" class="form-control" name="fpat" value="{{ $invoice->freight_payable_at }} " id="validationCustom05" required>
    <label id="fpat-error" class="error invalid-feedback animated fadeInDown" for="fpat"></label>
    </div>
  </div>
      <!-- <div class="col-md-3">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">place_of_issue<span class="text-danger">*</span></label>

    <input type="text" class="form-control" name="poi" id="validationCustom05" required>
    <label id="poi-error" class="error invalid-feedback animated fadeInDown" for="poi"></label>
    </div>
  </div> -->
  <!-- <div class="col-md-3">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">place_of_date<span class="text-danger">*</span> </label>

    <input type="date" class="form-control" name="podi" id="validationCustom05" required>
    <label id="podi-error" class="error invalid-feedback animated fadeInDown" for="podi"></label>
    </div>
  </div> -->

  <div class="col-md-12 mt-4">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Mode of Shipment <span class="text-danger">*</span></label>

    <input type="text" class="form-control" name="mode" value="{{ $invoice->mode_of_shipment }} " id="validationCustom05" required>
    <label id="mode-error" class="error invalid-feedback animated fadeInDown" for="freight"></label>
    </div>
  </div>

    
  <div class="col-md-12 mt-4">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Shipped on board <span class="text-danger">*</span></label>

    <input type="date" class="form-control" name="ship" value="{{ ($invoice->shipped_on_board != "")?date('Y-m-d',strtotime($invoice->shipped_on_board)):"" }}" id="validationCustom05" required>
    <label id="ship-error" class="error invalid-feedback animated fadeInDown" for="freight"></label>
    </div>
  </div>

</div>
</div>

