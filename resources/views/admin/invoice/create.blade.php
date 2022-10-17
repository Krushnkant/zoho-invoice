@extends('admin.layout')

@section('content')
<div class="row page-titles mx-0">
  <div class="col p-md-0">
    <ol class="breadcrumb">
    </ol>
  </div>
</div>

<div class="container">
  <div class="row mt-5 mb-5">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <center>
            <h1 class="" style="margin-bottom:25px;">
              BILL OF LANDING
            </h1>
          </center>

          <form class="row g-3 needs-validation" id="add_invoice_form" novalidate>
            {{ csrf_field() }}
            <input type="hidden" name="billno" value="{{ $billno }}">
            <div class="col-md-6">
              <div class="form-group">

                <label for="sname" class="form-label">Shipper <span class="text-danger">*</span></label>

                <input type="text" class="form-control" name="sname" id="sname" value="" required>
                <label id="sname-error" class="error invalid-feedback animated fadeInDown" for="sname"></label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="cname" class="form-label">Consignee <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="cname" id="cname" value="" required>
                <label id="cname-error" class="error invalid-feedback animated fadeInDown" for="cname"></label>
              </div>
            </div>
            
            <div class="col-md-6 mt-2">
              <div class="form-group">
                <label for="saddress" class="form-label">Shipper Address <span class="text-danger">*</span></label>

                <textarea rows="4" class="form-control summernote" cols="50" id="saddress" name="saddress" required></textarea>
                <label id="saddress-error" class="error invalid-feedback animated fadeInDown" for="saddress"></label>
              </div>
            </div>
            <div class="col-md-6 mt-2">
              <div class="form-group">
                <label for="caddress" class="form-label">Consignee Address <span class="text-danger">*</span></label>

                <textarea  class="form-control summernote" id="caddress" name="caddress" required>
</textarea>
                <label id="caddress-error" class="error invalid-feedback animated fadeInDown" for="caddress"></label>
              </div>
            </div>
            <div class="col-md-12 mt-2">
              <div class="form-group">
                <label for="select" class="form-label">Notify Address <span class="text-danger">*</span></label><br>
                <!--   <input type="radio" id="select" name="check_notify" value="1" class="check_notify">
                  <label for="html">Same as Shipper Address</label> -->
                  <input type="radio" id="select" name="check_notify" value="2" class="check_notify">
                  <label for="select">Same as Consignee </label>
                  <input type="radio" id="javascript" name="check_notify" value="3" class="check_notify" checked>
                  <label for="javascript">Create Notify Address</label>
                <div class="naddress">
                <textarea rows="4" class="form-control summernote" cols="50" id="naddress" name="naddress" required></textarea>
                <label id="naddress-error" class="error invalid-feedback animated fadeInDown" for="naddress"></label>
              </div>
              </div>
            </div>
          
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="pcarriageby" class="form-label">Pre carriage by</label>
                <input type="text" class="form-control" name="pcarriageby" id="pcarriageby" required>
                <label id="pcarriageby-error" class="error invalid-feedback animated fadeInDown" for="pcarriageby"></label>
              </div>
            </div>
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="por" class="form-label">Pier or Port of Receipt</label>
                <input type="text" class="form-control" name="por" id="por" required>
                <label id="por-error" class="error invalid-feedback animated fadeInDown" for="por"></label>
              </div>
            </div>

            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="cor" class="form-label">Country of Origin</label>
                <input type="text" class="form-control" name="cor" id="cor" required>
                <label id="cor-error" class="error invalid-feedback animated fadeInDown" for="cor"></label>
              </div>
            </div>
            <div class="col-md-12 mt-2">
              <div class="form-group">
                <label for="freight" class="form-label">Freight & charges<span class="text-danger">*</span></label>

                <select class="form-select form-control" name="freight" id="freight" required>
                  <option selected disabled value="">Choose...</option>
                  <option value="prepaid">Prepaid</option>
                  <option value="collect">Collect</option>
                </select>
                <label id="freight-error" class="error invalid-feedback animated fadeInDown" for="freight"></label>
              </div>
            </div>

                <div class="row" id="itemstbody" style="margin-left: initial;">
                  
            <div class="col-lg-2 mt-4">

            
              <div class="form-group">
                <label for="contain1" class="form-label">Container no </label>
                <input type="text" class="form-control" name="containerno[]" id="contain1" required>
                <label id="containerno-error" class="error invalid-feedback animated fadeInDown" for="containerno"></label>
              </div>

        

              <div class="form-group">
                <label for="containg" class="form-label">Number of packages</label>
                <input type="text" class="form-control" name="countainerpackage[]" id="containg" required>
                <label id="countainerpackage-error" class="error invalid-feedback animated fadeInDown" for="countainerpackage"></label>
              </div>
            </div>
            <div class="col-lg-2 mt-4">

            
          <div class="form-group">
            <label for="contain1" class="form-label">Container type </label>
            <input type="text" class="form-control" name="containertype[]" id="containtype" required>
            <label id="containertype-error" class="error invalid-feedback animated fadeInDown" for="containerno"></label>
          </div>

          <div class="form-group">
            <label for="containf" class="form-label" style="margin-top:2px">Gross Weight</label>

            <input type="text" class="form-control" name="gross[]" id="containf" required>
            <label id="gross-error" class="error invalid-feedback animated fadeInDown" for="gross"></label>
          </div>
          </div>
            <div class="col-lg-2 mt-4">
            
              
              <div class="form-group">
                <label for="containf" class="form-label" style="margin-top:2px">Seal no</label>

                <input type="text" class="form-control" name="seal[]" id="containseal" required>
                <label id="seal-error" class="error invalid-feedback animated fadeInDown" for="gross"></label>
              </div>
              <div class="form-group">
                <label for="netwt" class="form-label">Net Weight</label>
                <input type="text" class="form-control" name="netwt[]" id="containet" required>
                <label id="netwt-error" class="error invalid-feedback animated fadeInDown" for="countainerpackage"></label>
              </div>
            </div> 
            <div class="col-lg-2 mt-4">
              
              <div class="form-group">
                <label for="containd" class="form-label" style="margin-top:2px">Measurment</label>

                <input type="text" class="form-control" name="mesurment[]" id="containd" required>
                <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="mesurment"></label>
              </div>
            </div>
            <div class="col-md-4 mt-2">
              <div class="form-group">
                <label for="des" class="form-label">Marks & numbers/Kind of packages/description of goods</label>
                <textarea rows="5" class="form-control" name="description[]" cols="45" id="des" name="comment" required></textarea>
                <label id="description-error" class="error invalid-feedback animated fadeInDown" for="description"></label>
                
              </div>
            </div>
</div>
           
                <div class="col-12">
                <button type="button" class="btn btn-light list" id="addrow">Add row</button><br>
            </div>
            <div class="col-12" style="margin-top: 20px;">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                  <label class="form-check-label" for="invalidCheck">
                    Agree to terms and conditions
                  </label>
                  <div class="invalid-feedback">
                    You must agree before submitting.
                  </div><br>
                </div>
              </div>
            </div>
            
            <div class="col-12 mt-4">
              <button type="button" class="btn btn-lg btn-primary" id="saveInvoiceBtn">Save <i class=" fa fa-circle-o-notch fa-spin loadericonfa" style="display:none;"></i></button>
            </div>
          </form>
         
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
              // $(".loadericonfa").removeClass("hide");
           
              $('#saveInvoiceBtn').prop('disabled', true);
              $('#saveInvoiceBtn').find('.loadericonfa').show();
              var formData = new FormData($("#add_invoice_form")[0]);
              var check=$('#contain1').val();
              var check2=$('#containf').val();
              var check3=$('#containg').val();
              var check4=$('#containd').val();
              var check5=$('#containseal').val();
              var check6=$('#containtype').val();
              var check7=$('#containet').val();
         
              //alert(aboutme);
              var is_valid = true
              if(check == ""){
                  var is_valid = false; 
                  $('#containerno-error').show().text('this field is required');
                 
              }
              if(check2 == ""){
                  var is_valid = false; 
                  $('#gross-error').show().text('this field is required');
                 
              }
              if(check3 == ""){
                  var is_valid = false; 
                  $('#countainerpackage-error').show().text('this field is required');
                 
              }
              if(check4 == ""){
                  var is_valid = false; 
                  $('#mesurment-error').show().text('this field is required');
                 
              }
              if(check5 == ""){
                  var is_valid = false; 
                  $('#seal-error').show().text('this field is required');
                 
              }
              if(check6 == ""){
                  var is_valid = false; 
                  $('#netwt-error').show().text('this field is required');
                 
              }
              if(check7 == ""){
                  var is_valid = false; 
                  $('#containertype-error').show().text('this field is required');
                 
              }
              if($("#des").val().trim().length < 1)
              {
                var is_valid = false; 
                $('#description-error').show().text('this field is required');
              }

              if(is_valid){
                  

              $.ajax( {
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

                    if (res.errors.countainerpackage) {
                      $('#countainerpackage-error').show().text(res.errors.countainerpackage);
                    } else {
                      $('#countainerpackage-error').hide();
                    }
                    if (res.errors.description) {
                      $('#description-error').show().text(res.errors.description);
                    } else {
                      $('#description-error').hide();
                    }

                    if (res.errors.gross) {
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
            }else{
              
              $('#saveInvoiceBtn').prop('disabled', false);
              $('#saveInvoiceBtn').find('.loadericonfa').hide();
            }
            });

            $('.check_notify').change(function() {

              var textarea = $('.naddress');
              var select = $(this).val();

              textarea.hide();

              if (select == '3') {
                textarea.show();
              }
              if (select == '2') {
                textarea.hide();
              }
              if (select == '1') {
                textarea.hide();
              }

            })

          

            $("#addrow").click(function(){
      
            $("#language").prop('disabled',true);
            var addednum = $("#addednum").val();
            var language = $("#language").val();

            $.ajax({
                type: 'POST',
                url: "{{ route('invoice.add_row_item') }}",
                data: {_token: '{{ csrf_token() }}', total_item: addednum, language: language},
                success: function (res) {
                    
                    $("#itemstbody").append(res['html']);
                    $("#addednum").val(res['next_item']);
                    $('#item_name_'+res['next_item']).select2({
                        width: '100%',
                        placeholder: "Select...",
                        allowClear: false
                    });
                },
                error: function (data) {

                }
            });
});

jQuery(document).ready(function() {
    $(".summernote").summernote({
       
        height: 100,
        minHeight: null,
        maxHeight: null,
        toolbar: false,
        focus: !1
    }), $(".inline-editor").summernote({
        airMode: !0
    })
}), window.edit = function() {
    $(".click2edit").summernote()
}, window.save = function() {
    $(".click2edit").summernote("destroy")
};
</script>

@endsection

@section('js')

@endsection