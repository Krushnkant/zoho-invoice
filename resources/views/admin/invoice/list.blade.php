@extends('admin.layoutes')

@section('content')

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Invoice</a></li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            @if(isset($action) && $action=='create')
                                Add Invoice
                            @elseif(isset($action) && $action=='edit')
                                Edit Invoice
                            @else
                                Invoice List
                            @endif
                        </h4>                        
                        @if(isset($action) && $action=='list')
                            <div class="table-responsive">
                                <table id="Invoice" class="table zero-configuration customNewtable" style="width:100%">
                                    <thead>
                                    <tr>
                                        
                                        <th>No.</th>
                                     
                                        <th>Shipper Address</th>
                                        <th>Consignee Address</th>
                                        <th>Agent Address</th>
                                        <th>Bill_of_lading</th>
                                        <th>Country_Of_Origin</th>
                                        <th>Action</th>
                                        


                                    </tr>
                                    </thead>
                                   
                                    <tfoot>
                                    <tr>
                                     
                                  
                                        <th>No.</th>
                                  
                                        <th>Shipper Address</th>
                                        <th>Consignee Address</th>
                                        <th>Agent Address</th>
                                        <th>Bill_of_lading</th>
                                        <th>COUNTRY_OF_ORIGIN</th>
                                        
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @endif

                 

                        @if(isset($action) && $action=='edit')
                            @include('admin.invoice.edit')
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
<!-- Invoice JS start -->
<script type="text/javascript">
var table;

$(document).ready(function() {
    invoice_table(true);

function invoice_table(is_clearState=false){
   
    if(is_clearState){
        $('#Invoice').DataTable().state.clear();
    }
    var user_id_filter = $("#user_id_filter").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var hideFromExport = [6];

    table = $('#Invoice').DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        "pageLength": 100,
        'stateSave': function(){
            if(is_clearState){
                return false;
            }
            else{
                return true;
            }
        },
        buttons: [
            {
                extend: 'excel',
                // text: 'Export to Excel',
                exportOptions: {
                    /*columns: function ( idx, data, node ) {
                        var isVisible = table.column( idx ).visible();
                        var isNotForExport = $.inArray( idx, hideFromExport ) !== -1;
                        return ((isVisible && !isNotForExport) || !isVisible) ? true : false;
                    },*/
                    columns: [0,1,6,2,3,function ( idx, data, node ) {
                        var isVisible = table.column( idx ).visible();
                        return (!isVisible) ? true : false;
                    }],
                    modifier: {
                        page: 'current'
                    }
                }
            }
        ],
        "ajax":{
            "url": "{{ url('admin/allInvoicelist') }}",
            "dataType": "json",
            "type": "POST",
            "data":{ _token: '{{ csrf_token() }}', user_id_filter: user_id_filter, start_date: start_date, end_date: end_date},
            // "dataSrc": ""
        },
        'columnDefs': [
            { "width": "20px", "targets": 0 },
            { "width": "50px", "targets": 1 },
            { "width": "100px", "targets": 2 },
            { "width": "230px", "targets": 3 },
            { "width": "100px", "targets": 4 },
            { "width": "100px", "targets": 5 },
            { "width": "100px", "targets": 6},

        ],
        "columns": [
           
            {data: 'id', name: 'id', class: "text-center", orderable: false ,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'shipper_address', name: 'shipper_address', orderable: false, class: "text-left"},
            {data: 'consignee_address', name: 'consignee_address', orderable: false, class: "text-left multirow"},
            {data: 'agent_address', name: 'agent_address', orderable: false, class: "text-left "},
            {data: 'Bill_of_lading', name: 'Bill_of_lading', orderable: false, class: "text-left multirow"},
            {data: 'contry_origin', name: 'contry_origin', orderable: false, class: "text-left"},
            {data: 'Action', name: 'Action', orderable: false, class: "text-left"},

        ]
    });
}
});

$('body').on('click', '#editInvoiceBtn', function () {
    var invoice_id = $(this).attr('data-id');
    var url = "{{ url('admin/invoice/edit') }}" + "/" + invoice_id;
    window.open(url,"_blank");
});

$('body').on('click', '#invoice_submit', function () {
   
    $(this).prop('disabled',true);
    $(this).find('.loadericonfa').show();

    $("*#item_name-error").hide().html("");
    $("*#price-error").hide().html("");
    $("*#quantity-error").hide().html("");
    var btn = $(this);

    //var validate_invoice = validateInvoice();
    //ar validate_invoice_items = validateInvoiceItems($(btn).attr('action'));
       var formData = new FormData($('#invoiceForm')[0]);
    

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: "{{ route('admin.invoice.save') }}",
            data: formData,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            // contentType: 'json',
            success: function (res) {
                if(res['status']==200){
                    location.href = "{{ route('admin.invoice.list') }}";
                    if(res['Action'] == "add"){
                        toastr.success("Invoice Added",'Success',{timeOut: 5000});
                    }
                    else if(res['Action'] == "update"){
                        toastr.success("Invoice Updated",'Success',{timeOut: 5000});
                    }
                }
            },
            error: function (data) {
                $(btn).prop('disabled',false);
        
                $(btn).find('.loadericonfa').hide();
                toastr.error("Please try again",'Error',{timeOut: 5000});
            }
        });
    
    
        $(btn).prop('disabled',false);
        $(btn).find('.loadericonfa').hide();
    
});

$('body').on('click', '#printBtn', function (e) {
    e.preventDefault();
    var invoice_id = $(this).attr('data-id');
    var url = "{{ url('admin/invoice/pdf') }}" + "/" + invoice_id;
    window.open(url, "_blank");      
});

</script>
<!-- Invoice JS end -->
@endsection
