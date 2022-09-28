@extends('admin.layout')

@section('content')
<div class="row page-titles mx-0">
  <div class="col p-md-0">
    <ol class="breadcrumb">
      <!-- <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"></a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)"></a></li> -->
    </ol>
  </div>
</div>

<div class="container">
  <div class="row mt-5 mb-5">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <center>
            <h1 class="">
              INVOICE
            </h1>
          </center>





          <form class="row g-3 needs-validation" id="add_invoice_form" novalidate>
            {{ csrf_field() }}

            <div class="col-md-6">
              <div class="form-group">

                <label for="sname" class="form-label">Shipper name <span class="text-danger">*</span></label>

                <input type="text" class="form-control" name="sname" id="sname" value="" required>
                <label id="sname-error" class="error invalid-feedback animated fadeInDown" for="sname"></label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="validationCustom02" class="form-label">Consignee name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="cname" id="validationCustom02" value="" required>
                <label id="cname-error" class="error invalid-feedback animated fadeInDown" for="cname"></label>
              </div>
            </div>
            <!-- <div class="col-md-4">
  <div class="form-group">
    <label for="validationCustomUsername" class="form-label">Agent name<span class="text-danger">*</span></label>
    <div class="input-group has-validation">
      
      <input type="text" class="form-control" name="aname" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
      <label id="aname-error" class="error invalid-feedback animated fadeInDown" for="aname"></label>
    </div>
</div>
  </div> -->
            <div class="col-md-6 mt-2">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Shipper Address<span class="text-danger">*</span></label>

                <textarea rows="4" class="form-control" cols="50" id="validationCustom03" name="saddress" required>
</textarea>
                <label id="saddress-error" class="error invalid-feedback animated fadeInDown" for="saddress"></label>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Consignee Address<span class="text-danger">*</span></label>

                <textarea rows="4" class="form-control" cols="50" id="validationCustom03" name="caddress" required>
</textarea>
                <label id="caddress-error" class="error invalid-feedback animated fadeInDown" for="caddress"></label>
              </div>
            </div>
            <div class="col-md-12 mt-2">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Notify Address<span class="text-danger">*</span></label><br>
                <!--   <input type="radio" id="html" name="fav_language" value="1">
                  <label for="html">Same as Shipper Address</label><br>
                  <input type="radio" id="css" name="fav_language" value="2">
                  <label for="css">Same as Consignee Address</label><br>
                  <input type="radio" id="javascript" name="fav_language" value="3">
                  <label for="javascript">Create Notify Address</label> -->
                <textarea rows="4" class="form-control" cols="50" id="validationCustom03" name="naddress" required>
              </textarea>
                <label id="naddress-error" class="error invalid-feedback animated fadeInDown" for="naddress"></label>
              </div>
            </div>
            <!-- <div class="col-md-6">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Agent Address<span class="text-danger">*</span></label>

    <textarea rows="4"  class="form-control" cols="50" id="validationCustom03" name="agaddress" required>
</textarea>
<label id="agaddress-error" class="error invalid-feedback animated fadeInDown" for="agaddress"></label>
  </div>
  </div> -->
            <!-- <div class="col-md-3">
    <label for="validationCustom04" class="form-label">State</label>
    <select class="form-select" id="validationCustom04" required>
      <option selected disabled value="">Choose...</option>
      <option>...</option>
    </select>
    <div class="invalid-feedback">
      Please select a valid state.
    </div>
  </div> -->
            <!-- <div class="col-md-6 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Bill Number<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="bnumber" value="PSLPKLJPR" id="validationCustom05" required>
                <label id="bnumber-error" class="error invalid-feedback animated fadeInDown" for="bnumber"></label>
              </div>
            </div> -->
            <!-- <div class="col-md-6 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Bill Of Lading<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="blading" id="validationCustom05" required>
                <label id="blading-error" class="error invalid-feedback animated fadeInDown" for="blading"></label>
              </div>
            </div> -->
            <!-- <div class="col-md-3">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">veseel<span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="vessel" id="validationCustom05" required>
    <label id="vessel-error" class="error invalid-feedback animated fadeInDown" for="vessel"></label>
    
</div>
  </div> -->
            <!-- <div class="col-md-3">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Voyage<span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="voyage" id="validationCustom05" required>
    <label id="voyage-error" class="error invalid-feedback animated fadeInDown" for="voyage"></label>
  </div>
  </div> -->
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Pre carriage by</label>
                <input type="text" class="form-control" name="pcarriageby" id="validationCustom05" required>
                <label id="pcarriageby-error" class="error invalid-feedback animated fadeInDown" for="pcarriageby"></label>
              </div>
            </div>
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Pier or Port of Receipt</label>
                <input type="text" class="form-control" name="por" id="validationCustom05" required>
                <label id="por-error" class="error invalid-feedback animated fadeInDown" for="por"></label>
              </div>
            </div>
            <!-- <div class="col-md-3">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Port of Loading<span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="pol" id="validationCustom05" required>
    <label id="pol-error" class="error invalid-feedback animated fadeInDown" for="pol"></label>
    </div>
  </div> -->
            <!-- <div class="col-md-3">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">Port of Discharge<span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="pod" id="validationCustom05" required>
    <label id="pod-error" class="error invalid-feedback animated fadeInDown" for="pod"></label>
    </div>
  </div> -->
            <!-- <div class="col-md-3">
  <div class="form-group">
    <label for="validationCustom05" class="form-label">piace of delivery<span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="pody" id="validationCustom05" required>
    <label id="pody-error" class="error invalid-feedback animated fadeInDown" for="pody"></label>
    </div>
  </div> -->
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Country of Origin</label>
                <input type="text" class="form-control" name="cor" id="validationCustom05" required>
                <label id="cor-error" class="error invalid-feedback animated fadeInDown" for="cor"></label>
              </div>
            </div>
            <div class="col-md-12 mt-2">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Freight & charges<span class="text-danger">*</span></label>

                <select class="form-select form-control" name="freight" id="validationCustom04" required>
                  <option selected disabled value="">Choose...</option>
                  <option value="prepaid">Prepaid</option>
                  <option value="collect">Collect</option>
                </select>
                <label id="freight-error" class="error invalid-feedback animated fadeInDown" for="freight"></label>
              </div>
            </div>
            <!-- <div class="col-md-6">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Freight_payble_at<span class="text-danger">*</span> </label>

    <input type="text" class="form-control" name="fpat" id="validationCustom05" required>
    <label id="fpat-error" class="error invalid-feedback animated fadeInDown" for="fpat"></label>
    </div>
  </div> -->
            <div class="col-md-4 mt-4">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Countainer no/Seal No Marks & Numbers</label>
                <input type="text" class="form-control" name="containerno[]" id="validationCustom05" required>
                <label id="containerno-error" class="error invalid-feedback animated fadeInDown" for="containerno"></label>
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="gross-error" class="error invalid-feedback animated fadeInDown" for="gross"></label>
              </div>
            </div>
            
            <div class="col-md-4 mt-4">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Number of containers or packages</label>
                <input type="text" class="form-control" name="countainerpackage[]" id="validationCustom05" required>
                <label id="countainerpackage-error" class="error invalid-feedback animated fadeInDown" for="countainerpackage"></label>
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="mesurment"></label>
              </div>
            </div>
            <div class="col-md-4 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Kind of packages/description of goods</label>

                <textarea rows="5" class="form-control" name="description[]" cols="50" id="validationCustom03" name="comment" required>
</textarea>
                <label id="description-error" class="error invalid-feedback animated fadeInDown" for="description"></label>
              </div>
            </div>
            <!-- <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="gross-error" class="error invalid-feedback animated fadeInDown" for="gross"></label>
              </div>
            </div> -->
            <!-- <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="mesurment"></label>
              </div>
            </div> -->
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Countainer no/Seal No Marks & Numbers</label>
                <input type="text" class="form-control" name="containerno[]" id="validationCustom05" required>
                <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="gross-error" class="error invalid-feedback animated fadeInDown" for="gross"></label>
              </div>
              
            </div>
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Number of containers or packages</label>
                <input type="text" class="form-control" name="countainerpackage[]" id="validationCustom05" required>
                <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
              </div>
            </div>
            <div class="col-md-4 mt-3">
              <div class="form-group">
                <label for="validationCustom03 " class="form-label">Kind of packages/description of goods</label>

                <textarea rows="5" class="form-control" name="description[]" cols="50" id="validationCustom03" name="comment" required>
</textarea>
                <label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
              </div>
            </div>
            <!-- <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
              </div>
            </div>
            <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
              </div>
            </div> -->
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Countainer no/Seal No Marks & Numbers</label>
                <input type="text" class="form-control" name="containerno[]" id="validationCustom05" required>
                <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
              </div>
            </div>
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Number of containers or packages</label>
                <input type="text" class="form-control" name="countainerpackage[]" id="validationCustom05" required>
                <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
              </div>
            </div>
            <div class="col-md-4 mt-3">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Kind of packages/description of goods</label>

                <textarea rows="5" class="form-control" name="description[]" cols="50" id="validationCustom03" name="comment" required>
</textarea>
                <label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
              </div>
            </div>
            <!-- <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
              </div>
            </div>
            <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
              </div>
            </div> -->
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Countainer no/Seal No Marks & Numbers</label>
                <input type="text" class="form-control" name="containerno[]" id="validationCustom05" required>
                <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
              </div>
            </div>
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Number of containers or packages</label>
                <input type="text" class="form-control" name="countainerpackage[]" id="validationCustom05" required>
                <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
                
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
              </div>
            </div>
            <div class="col-md-4 mt-3">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Kind of packages/description of goods</label>

                <textarea rows="5" class="form-control" name="description[]" cols="50" id="validationCustom03" name="comment" required>
</textarea>
                <label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
              </div>
            </div>
            <!-- <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
              </div>
            </div>
            <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
              </div>
            </div> -->
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Countainer no/Seal No Marks & Numbers</label>
                <input type="text" class="form-control" name="containerno[]" id="validationCustom05" required>
                <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
              </div>
            </div>
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="validationCustom05" class="form-label">Number of containers or packages</label>
                <input type="text" class="form-control" name="countainerpackage[]" id="validationCustom05" required>
                <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
              </div>
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
              </div>
            </div>
            <div class="col-md-4 mt-3">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Kind of packages/description of goods</label>

                <textarea rows="5" class="form-control" name="description[]" cols="50" id="validationCustom03" name="comment" required>
</textarea>
                <label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
              </div>
            </div>
            <!-- <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Gross Webeight</label>

                <input type="text" class="form-control" name="gross[]" id="validationCustom05" required>
                <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
              </div>
            </div>
            <div class="col-md-2 mt-4">
              <div class="form-group">
                <label for="validationCustom03" class="form-label">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="validationCustom05" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
              </div>
            </div> -->
            <!-- <div class="col-md-6">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Freight & charges<span class="text-danger">*</span></label>

    <input type="text" class="form-control" name="freight" id="validationCustom05" required>
    <label id="freight-error" class="error invalid-feedback animated fadeInDown" for="freight"></label>
    </div>
  </div> -->


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


            <!-- <div class="col-md-6">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">Mode_of_shipment<span class="text-danger">*</span> </label>

    <input type="text" class="form-control" name="mode" id="validationCustom05" required>
    <label id="mode-error" class="error invalid-feedback animated fadeInDown" for="mode"></label>
</div>
  </div> -->
            <!-- <div class="col-md-6">
  <div class="form-group">
    <label for="validationCustom03" class="form-label">shipped_on_board<span class="text-danger">*</span> </label>

    <input type="text" class="form-control" name="sonboard" id="validationCustom05" required>
    <label id="sonboard-error" class="error invalid-feedback animated fadeInDown" for="sonboard"></label>
    </div>
  </div> -->
            <div class="col-12">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                  <label class="form-check-label" for="invalidCheck">
                    Agree to terms and conditions
                  </label>
                  <div class="invalid-feedback">
                    You must agree before submitting.
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 mt-4">
              <button class="btn btn-lg btn-primary" id="saveInvoiceBtn">Save <i class="fa fa-circle-o-notch fa-spin loadericonfa" style="display:none;"></i></button>
            </div>
          </form>
          <script>
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function() {
              'use strict'

              // Fetch all the forms we want to apply custom Bootstrap validation styles to
              var forms = document.querySelectorAll('.needs-validation')

              // Loop over them and prevent submission
              Array.prototype.slice.call(forms)
                .forEach(function(form) {
                  form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                      event.preventDefault()
                      event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                  }, false)
                })
            })()
          </script>
          <script>
            // $(function() {

            //   // add new invoice ajax request
            //   $("#add_invoice_form").submit(function(e) {
            //     e.preventDefault();
            //     const fd = new FormData(this);
            //     $("#add_employee_btn").text('Adding...');
            //     $.ajax({
            //      
            //       method: 'post',
            //       data: fd,
            //       cache: false,
            //       contentType: false,
            //       processData: false,
            //       dataType: 'json',
            //       success: function(response) {
            //         if (response.status == 200) {
            //           Swal.fire(
            //             'Added!',
            //             'iNVOICE Added Successfully!',
            //             'success'
            //           )
            //           fetchAllEmployees();
            //         }
            //         $("#add_employee_btn").text('Add Employee');
            //         $("#add_employee_form")[0].reset();
            //         $("#addEmployeeModal").modal('hide');
            //       }
            //     });
            //   });
            // });
          </script>
          <script>
            $('body').on('click', '#saveInvoiceBtn', function() {
              $('#saveInvoiceBtn').prop('disabled', true);
              $('#saveInvoiceBtn').find('.loadericonfa').show();
              var formData = new FormData($("#add_invoice_form")[0]);

              $.ajax({
                type: 'POST',
                url: "{{ url('invoice/create') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                  if (res.status == 'failed') {
                    $('#saveInvoiceBtn').prop('disabled', false);
                    $('#saveInvoiceBtn').find('.loadericonfa').hide();
                    if (res.errors.sname) {
                      $('#sname-error').show().text(res.errors.sname);
                    } else {
                      $('#sname-error').hide();
                    }

                    if (res.errors.cname) {
                      $('#cname-error').show().text(res.errors.cname);
                    } else {
                      $('#cname-error').hide();
                    }

                    if (res.errors.aname) {
                      $('#aname-error').show().text(res.errors.aname);
                    } else {
                      $('#aname-error').hide();
                    }

                    if (res.errors.saddress) {
                      $('#saddress-error').show().text(res.errors.saddress);
                    } else {
                      $('#saddress-error').hide();
                    }

                    if (res.errors.caddress) {
                      $('#caddress-error').show().text(res.errors.caddress);
                    } else {
                      $('#caddress-error').hide();
                    }

                    if (res.errors.naddress) {
                      $('#naddress-error').show().text(res.errors.naddress);
                    } else {
                      $('#naddress-error').hide();
                    }
                    if (res.errors.agaddress) {
                      $('#agaddress-error').show().text(res.errors.agaddress);
                    } else {
                      $('#agaddress-error').hide();
                    }

                    if (res.errors.blading) {
                      $('#blading-error').show().text(res.errors.blading);
                    } else {
                      $('#blading-error').hide();
                    }
                    // if (res.errors.vessel) {
                    //     $('#vessel-error').show().text(res.errors.vessel);
                    // } else {
                    //     $('#vessel-error').hide();
                    // }

                    // if (res.errors.voyage) {
                    //     $('#voyage-error').show().text(res.errors.voyage);
                    // } else {
                    //     $('#voyage-error').hide();
                    // }
                    if (res.errors.pcarriageby) {
                      $('#pcarriageby-error').show().text(res.errors.pcarriageby);
                    } else {
                      $('#pcarriageby-error').hide();
                    }

                    if (res.errors.por) {
                      $('#por-error').show().text(res.errors.por);
                    } else {
                      $('#por-error').hide();
                    }
                    // if (res.errors.pol) {
                    //     $('#pol-error').show().text(res.errors.pol);
                    // } else {
                    //     $('#pol-error').hide();
                    // }

                    // if (res.errors.pod) {
                    //     $('#pod-error').show().text(res.errors.pod);
                    // } else {
                    //     $('#pod-error').hide();
                    // }
                    // if (res.errors.pody) {
                    //     $('#pody-error').show().text(res.errors.pody);
                    // } else {
                    //     $('#pody-error').hide();
                    // }

                    if (res.errors.cor) {
                      $('#cor-error').show().text(res.errors.cor);
                    } else {
                      $('#cor-error').hide();
                    }
                    if (res.errors.containerno) {
                      $('#containerno-error').show().text(res.errors.containerno);
                    } else {
                      $('#containerno-error').hide();
                    }

                    if (res.errors.nocp) {
                      $('#countainerpackage-error').show().text(res.errors.countainerpackage);
                    } else {
                      $('#countainerpackage-error').hide();
                    }
                    if (res.errors.dog) {
                      $('#description-error').show().text(res.errors.description);
                    } else {
                      $('#description-error').hide();
                    }

                    if (res.errors.grossweb) {
                      $('#gross-error').show().text(res.errors.gross);
                    } else {
                      $('#gross-error').hide();
                    }
                    if (res.errors.mesurment) {
                      $('#mesurment-error').show().text(res.errors.mesurment);
                    } else {
                      $('#mesurment-error').hide();
                    }

                    if (res.errors.freight) {
                      $('#freight-error').show().text(res.errors.freight);
                    } else {
                      $('#freight-error').hide();
                    }
                    // if (res.errors.poi) {
                    //     $('#poi-error').show().text(res.errors.poi);
                    // } else {
                    //     $('#poi-error').hide();
                    // }
                    // if (res.errors.podi) {
                    //     $('#podi-error').show().text(res.errors.podi);
                    // } else {
                    //     $('#podi-error').hide();
                    // }

                    if (res.errors.fpat) {
                      $('#fpat-error').show().text(res.errors.fpat);
                    } else {
                      $('#fpat-error').hide();
                    }
                    // if (res.errors.mode) {
                    //     $('#mode-error').show().text(res.errors.mode);
                    // } else {
                    //     $('#mode-error').hide();
                    // }
                    // if (res.errors.sonboard) {
                    //     $('#sonboard-error').show().text(res.errors.sonboard);
                    // } else {
                    //     $('#sonboard-error').hide();
                    // }

                  }

                  if (res.status == 200) {

                    $('#saveInvoiceBtn').prop('disabled', false);
                    document.getElementById("add_invoice_form").reset();
                    $('#saveInvoiceBtn').find('.loadericonfa').hide();
                    toastr.success("Invoice Created", 'Success', {
                      timeOut: 5000
                    });
                  }

                  if (res.status == 400) {

                    $("#InvoiceModal").modal('hide');
                    $('#saveInvoiceBtn').prop('disabled', false);
                    $('#saveInvoiceBtn').find('.loadericonfa').hide();
                    toastr.error("Please try again", 'Error', {
                      timeOut: 5000
                    });
                  }
                },
                error: function(data) {

                  $("#InvoiceModal").modal('hide');
                  $('#saveInvoiceBtn').prop('disabled', false);
                  $('#saveInvoiceBtn').find('.loadericonfa').hide();
                  toastr.error("Please try again", 'Error', {
                    timeOut: 5000
                  });
                }
              });
            });

            // $('#select').change(function(){

            // var textarea = $('textarea');
            // var select   = $(this).val();

            // textarea.hide();

            // if (select == '3'){
            //   textarea.show();
            // }
            // if (select == '2'){
            //   textarea.hide();
            // }
            // if (select == '1'){
            //   textarea.hide();
            // }

            // });​

          </script>
          
          @endsection

          @section('js')

          @endsection