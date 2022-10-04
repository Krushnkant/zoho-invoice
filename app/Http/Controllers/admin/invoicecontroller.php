<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\invoice;
use App\Models\item;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

use Illuminate\Support\Facades\Validator;

class invoicecontroller extends Controller
{
    public function index($billno){
      
       // $client= new GuzzleHttp\Client();
        // $res=
        // $client->request('GET',' https://books.zoho.in/api/v3/salesorders/938173000000186007?organization_id=60015618450');
        // echo $res->getBody();

    //     $response = 
    //         Http::get('https://books.zoho.in/api/v3/salesorders/938173000000186007?organization_id=60015618450');
    //$response = Http::accept('application/json')->get('https://books.zoho.in/api/v3/salesorders/938173000000186007?organization_id=60015618450');
    //       dd($response);
    //    return response()->json($response);

//     $response=  Http::withHeaders([
//         'Authorization' =>  'Zoho-oauthtoken ' . $accessToken,
//         'Content-Type' => 'application/json' 
//    ])->get('https://books.zoho.in/api/v3/salesorders/938173000000186007?organization_id=60015618450');

        return view('admin.invoice.create',compact('billno'));
    }
    public function list()
    {
        $action = "list";
        return view('admin.invoice.list',compact('action'));
    }

    public function allInvoicelist(Request $request){
        if ($request->ajax()) {
            $columns = array(
                0 =>'id',
                1 =>'shipper_address',
                2 =>'consignee_address',
                3=> 'agent_address',
                4=> 'Bill_of_lading',
                5=> 'contry_origin',
                6=> 'Action',
           
            );

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if($order == "id"){
                $order = "created_at";
                $dir = 'DESC';
            }

            $totalData = Invoice::count();
            $totalFiltered = $totalData;

            if(empty($request->input('search.value')))
            {
                $Invoices = Invoice::offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();

                $totalFiltered = count($Invoices->toArray());
            }
            else {
                $search = $request->input('search.value');
                $Invoices = Invoice::where(function($query) use($search){
                    $query->where('shipper_address','LIKE',"%{$search}%")
                        ->orWhere('consignee_address', 'LIKE',"%{$search}%")
                        ->orWhere('agent_address', 'LIKE',"%{$search}%")
                        ->orWhere('Bill_of_lading', 'LIKE',"%{$search}%")
                        ->orWhere('contry_origin', 'LIKE',"%{$search}%");
                       
                       
                     
                    })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order,$dir)
                    ->get();
                
                $totalFiltered = count($Invoices->toArray());

            }

            $data = array();

            if(!empty($Invoices))
            {
                foreach ($Invoices as $Invoice)
                {
                    

                    $action = '';
                    $action .= '<button id="printBtn" class="btn btn-gray text-warning btn-sm" data-id="'.$Invoice->id.'"><i class="fa fa-print" aria-hidden="true"></i></button>';
                    $action .= '<button id="editInvoiceBtn" class="btn btn-gray text-blue btn-sm" data-id="'.$Invoice->id.'"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
                    $nestedData['shipper_address'] = $Invoice->shipper_address;
                    $nestedData['consignee_address'] = $Invoice->consignee_address;
                    $nestedData['agent_address'] = $Invoice->agent_address;
                    $nestedData['Bill_of_lading'] = $Invoice->Bill_of_lading;
                    $nestedData['contry_origin'] = $Invoice->contry_origin;
                    $nestedData['Action'] = $action;

  

                    $data[] = $nestedData;
                    
                }
            }
           // dd($data);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data,
            );

            echo json_encode($json_data);
        }
    }
    //handle update data
    public function save(Request $request)
    {   $messages = [
        'sname.required' =>'this field is required',
        'cname.required' =>'this field is required',
        'saddress.required' =>'this field is required',
        'caddress.required' =>'this field is required',
        //'naddress.required' =>'this field is required',
        'aname.required' =>'this field is required',
        'agaddress.required' =>'this field is required',
        'blading.required' =>'this field is required',
        'vessel.required' =>'this field is required',
        'voyage.required' =>'this field is required',
        'pol.required' =>'this field is required ',
        'pod.required' =>'this field is required ',
        'pody.required' =>'this field is required',
        'pcarriageby.required' =>'this field is required ',
        'por.required' =>'this field is required ',
        'cor.required' =>'this field is required',
        'containerno.required' =>'this field is required ',
        'countainerpackage.required' =>'this field is required',
        'description.required' =>'this field is required ',
        'gross.required' =>'this field is required ',
        'mesurment.required' =>'this field is required',
        'freight.required' =>'this field is required ',
        'pdate.required' =>'this field is required ',
        'place.required' =>'this field is required',
        'sonboard.required' =>'this field is required ',
        'mode.required' =>'this field is required',
        'fpat.required' =>'this field is required',
        'sname.required' =>'this field is required',
        'ship.required' =>'this field is required',
        'mode.required' =>'this field is required',
    ];
    //dd($request->all());
    $validator = Validator::make($request->all(), [
        
        'sname' => 'required',
        'saddress' => 'required',
        'cname' => 'required',
        'caddress' => 'required',
        //'naddress' => 'required',
        'aname' => 'required',
        'agaddress' => 'required',
        // 'bnumber' => 'required',
        'blading' => 'required',
        // 'vessel' => 'required',`
        // 'voyage' => 'required',
        // 'pol' => 'required',
        // 'pod' => 'required',
        // 'pody' => 'required',
        'pcarriageby' => 'required',
        'por' => 'required',
        'cor' => 'required',
    //     'containerno' => 'required',
    //    'countainerpackage' => 'required',
    //     'description' => 'required',
    //    'gross' => 'required',
    //    'mesurment' => 'required',
        'freight' => 'required',
         'pdate' => 'required',
         'place' => 'required',
        // 'sonboard' => 'required',
         'mode' => 'required',
        'fpat' => 'required',
        'ship' => 'required',
     

    ], $messages);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors(),'status'=>'failed']); 
    }      

    $id = $request->invoice_id;
	
    item::where('invoiceid', $id)->delete();
       //dd($request->all());
       $company = Invoice::find($request->invoice_id);
        $company->shipper_name = $request->sname;
        $company->consignee_name = $request->cname;
        $company->agent_name = $request->aname;
        $company->shipper_address = $request->saddress;
        $company->consignee_address = $request->caddress;
        $company->notify_address = $request->naddress;
        $company->agent_address = $request->agaddress;
        $company->Bill_of_lading = $request->blading;
        $company->pre_carriage_by = $request->pcarriageby;
        $company->port_of_receipt = $request->por;
        $company->contry_origin = $request->cor;
        $company->freight_charges = $request->freight;
        $company->freight_payable_at = $request->fpat;
         $company->shipped_on_board = $request->ship;
        $company->mode_of_shipment = $request->mode;
        $company->place_of_date = $request->pdate;
        $company->place_of_issue = $request->place;
        $company->save();
       // dd($company);
        foreach ($request->containerno as $key => $item) {
               
            if($item != "" && $item != null){          
            $item_detail = new item;
            $item_detail->invoiceid = $request->invoice_id;
            $item_detail->container_no = $item;
            $item_detail->container_package = $request->countainerpackage[$key];
            $item_detail->description_goods = $request->description[$key];
            $item_detail->Gross_web  = $request->gross[$key];
            $item_detail->Measurment = $request->mesurment[$key];
            $item_detail->save();
            }
           
    }
    //dd($company);
  

   
    
        // handle delete an employee ajax request
	
	
        
        // Invoice::whereId($id)->update($updateData);
       
        return response()->json([
            'status'=>200,
            'message'=>'Invoice updated Successfully.'
        ]); 


    }
    public function edit($id)
    {
        	
    //   $users = Invoice::leftjoin('item_details', 'item_details.invoiceid', '=', 'invoice_data.id')
    //  ->get(['item_details.*', 'invoice_data.container_no']);
             
        $invoice = Invoice::with('item')->where('id',$id)->first();
       //dd($invoice->item[0]);
        $action = "edit";   
         //$invoice = Invoice::->first();              
        //dd($invoice);
        return view('admin.invoice.list',compact('action','invoice'));     
    }

    public function add_row_item(Request $request){
        $language = $request->language;
        $next_item = $request->total_item + 1;

       // $products = invoice::where('estatus',1)->get();
   

    $html = '<div class="col-md-4 mt-4">
        <div class="form-group">
        <label for="containerno5" class="form-label">Container no/Seal No </label>
        <input type="text" class="form-control contain4" name="containerno[]" id="containerno5" required>
        <label id="cn-error" class="error invalid-feedback animated fadeInDown" for="cn"></label>
      </div>
      <div class="form-group">
        <label for="gross5" class="form-label" style="margin-top:2px">Gross Weight</label>

        <input type="text" class="form-control contain4" name="gross[]" id="gross5" required>
        <label id="grossweb-error" class="error invalid-feedback animated fadeInDown" for="grossweb"></label>
      </div>
    </div>
    <div class="col-md-4 mt-4">
      <div class="form-group">
        <label for="countainerpackage5" class="form-label">Number of  packages</label>
        <input type="text" class="form-control contain4" name="countainerpackage[]" id="countainerpackage5" required>
        <label id="nocp-error" class="error invalid-feedback animated fadeInDown" for="nocp"></label>
      </div>
      <div class="form-group">
        <label for="mesurment5" class="form-label" style="margin-top:2px">Measurment</label>

        <input type="text" class="form-control contain4" name="mesurment[]" id="mesurment5" required>
        <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="gromesurmentssweb"></label>
      </div>
    </div>
    <div class="col-md-4 mt-4">
      <div class="form-group">
        <label for="description5" class="form-label">Kind of packages/description of goods</label>

        <textarea rows="5" class="form-control contain4" name="description[]" cols="50" id="description5" name="comment" required>
        </textarea>
        <label id="dog-error" class="error invalid-feedback animated fadeInDown" for="dog"></label>
      </div>
    </div>';

        return ['html' => $html, 'next_item' => $next_item];
    }

    public function store(Request $request)
    {
        
       
    
        //dd($request->containerno);
        $messages = [
            'sname.required' =>'this field is required',
            'cname.required' =>'this field is required',
            'saddress.required' =>'this field is required',
            'caddress.required' =>'this field is required',
            //'naddress.required' =>'Please provide N address',
            //'aname.required' =>'please provide a prefix for A Name',
            //'agaddress.required' =>'Please provide a address',
           // 'blading.required' =>'this field is required',
            'vessel.required' =>'this field is required',
            'voyage.required' =>'this field is required',
            'pol.required' =>'this field is required ',
            'pod.required' =>'this field is required ',
            'pody.required' =>'this field is required',
            //'pcarriageby.required' =>'this field is required ',
            //'por.required' =>'this field is required ', 
            //'cor.required' =>'this field is required', 
            'containerno.required' =>'this field is required ',
            'countainerpackage.required' =>'this field is required',
            'description.required' =>'this field is required ',
            'gross.required' =>'this field is required ',
            'mesurment.required' =>'this field is required',
            'freight.required' =>'this field is required ',
            'poi.required' =>'this field is required ',
            'podi.required' =>'this field is required',
            'sonboard.required' =>'this field is required ',
            'mode.required' =>'this field is required',
            //'fpat.required' =>'this field is required',
            'sname.required' =>'this field is required',
        ];
     
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'sname' => 'required',
            'saddress' => 'required',
            'cname' => 'required',
            'caddress' => 'required',
            //'naddress' => 'required',
            //'aname' => 'required',
            //'agaddress' => 'required',
            // 'bnumber' => 'required',
            //'blading' => 'required',
            // 'vessel' => 'required',`
            // 'voyage' => 'required',
            // 'pol' => 'required',
            // 'pod' => 'required',
            // 'pody' => 'required',
           // 'pcarriageby' => 'required',
            //'por' => 'required',
            //'cor' => 'required',
            'containerno' => 'required|array',
           'countainerpackage' => 'required',
            'description' => 'required|array',
           'gross' => 'required',
           'mesurment' => 'required',
            'freight' => 'required',

         

        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),'status'=>'failed']);
        }
         //dd("hehh");
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://accounts.zoho.in/oauth/v2/token?refresh_token=1000.ff46e1048cddd9a9b86ba4abe804c4ba.a923be1535a22a81dde6b032a96baf63&client_id=1000.F998L05TJ3JSGW0RE7NWAJZ7WYB9YV&client_secret=492623e95cbb29a2953d664489c565b018e088f20e&grant_type=refresh_token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Cookie: 6e73717622=4440853cd702ab2a51402c119608ee85; _zcsr_tmp=4e12e670-8911-4bb6-81ae-1112e8bef96d; iamcsr=4e12e670-8911-4bb6-81ae-1112e8bef96d'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $responsetoken = json_decode($response,true);
        //dd($responsetoken);
        $accessToken = $responsetoken['access_token'];

        $bill = $request->input('billno');
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://books.zoho.in/api/v3/salesorders/'.$bill.'?organization_id=60015618450',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Zoho-oauthtoken ' . $accessToken,
            'Content-Type: application/json',
            'Cookie: BuildCookie_60015618450=1; 54900d29bf=6546c601cb473cceb7511983f377761e; JSESSIONID=4165A10F45BF9DC696D33277DEC05C7F; _zcsr_tmp=0b60f2e4-8676-4c08-b641-640f8c42997f; zbcscook=0b60f2e4-8676-4c08-b641-640f8c42997f'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response1 = json_decode($response,true);
       //dd($response1['salesorder']['custom_fields']);
       $var  = "";
       $var2  = "";
       $var3  = "";
       $var4  = "";
       $var5  = "";
        
        foreach($response1['salesorder']['custom_fields'] as $key => $item)
        {
            // dump($item['label']);
           
             $item['label'];
             if($item['label'] == "Final Destination"){
                $var = $item['value'];
               
             }
             if($item['label'] == "Vessel / Voy"){
                $var2 = $item['value'];
                //dd($var2);
             }
          
             if($item['label'] == "Vessel / Voy"){
                $var3 = $item['value'];
            
             }

             if($item['label'] == "POL"){
                $var4 = $item['value'];
                // dump("value 1");
               
             }

             if($item['label'] == "POD"){
                $var5 = $item['value'];
                // dump("value 2");
             }

             


        }
        //dd($var2);
             $lastUsedSerialNumber = invoice::query()->orderByDesc('id')->first();
             if($lastUsedSerialNumber){
                $last_id =  $lastUsedSerialNumber->id + 1;
             }
             else{
                $last_id =   1;
             }
             
            $invoice = new invoice;
            // $lastInvoiceID = $invoice->orderBy('id', DESC)->pluck('id')->first();
            // $newInvoiceID = $lastInvoiceID + 1;

            $invoice->shipper_name = $request->input('sname');
            $invoice->shipper_address = $request->input('saddress');
            $invoice->consignee_name = $request->input('cname');
            $invoice->consignee_address = $request->input('caddress');
            if($data['check_notify'] == "1"){
            $invoice->notify_address = $request->input('saddress');
            }
            elseif($data['check_notify'] == "2"){
            $invoice->notify_address = $request->input('caddress');
            }   
            else{
            $invoice->notify_address = $request->input('naddress');
            } 

            // $invoice->agent_name = $request->input('aname');
            //$invoice->agent_address = $response1['Vessel'];
            $invoice->bill_number  =  $request->input('billno').date('Y-m-d').   $last_id;
            //$invoice->Bill_of_lading = $request->input('blading');
            $invoice->vessel =  $var2;
            $invoice->voyage = $var3;
            $invoice->port_of_loading = $var4;
            $invoice->port_of_dischange = $var5;
            $invoice->delivery_place = $var;
            $invoice->pre_carriage_by = $request->input('pcarriageby');
            $invoice->port_of_receipt  = $request->input('por');
            $invoice->contry_origin = $request->input('cor');
            // $invoice->container_no = $request->input('cn');
            // $invoice->container_package = $request->input('nocp');
            // $invoice->description_goods = $request->input('dog');
            // $invoice->Gross_web  = $request->input('grossweb');
             $invoice->freight_charges = $request->input('freight');
          
            if($data['freight'] == "prepaid"){
                $invoice->freight_payable_at = $var4;
            }else{
                $invoice->freight_payable_at = $var5;
            }
            $invoice->place_of_issue = $request->input('poi');
            $invoice->place_of_date  = $request->input('podi');
            //$invoice->shipped_on_board = $var5;
        $invoice->mode_of_shipment	="FCL/FCL";
      
            $invoice->save();
            $invoice->id;
            dd($invoice);
            foreach ($request->containerno as $key => $item) {
               
                     if($item != "" && $item != null){          
                    $item_detail = new item;
                    $item_detail->invoiceid = $invoice->id;
                    $item_detail->container_no = $item;
                    $item_detail->container_package = $request->countainerpackage[$key];
                    $item_detail->description_goods = $request->description[$key];
                    $item_detail->Gross_web  = $request->gross[$key];
                    $item_detail->Measurment = $request->mesurment[$key];
                    $item_detail->save();
                     }
                 // dump( $item_detail);
            }
            //dd("$item_detail");
             return response()->json([
                'status'=>200,
                'message'=>'Invoice Added Successfully.'
            ]); 
    }

  


    public function generate_pdf($id){

              $users = Invoice::with('item')->where('id',$id)->first();
             // dd($users->item);
     $invoice = Invoice::where('id',$id)->first();
        $settings = Setting::find(1);

        $image = '';
        if (isset($settings->company_logo)){
            $image = '<img src="'.url('public/images/company/'.$settings->company_logo).'" alt="Logo" width="100px" height="100px">';
        }
        

        $HTMLContent = '<html>

        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
           

            <link rel="stylesheet" href="css/style.css">';
        
            $HTMLContent .= '<style type="text/css">

        
            body {
                font-family: "Roboto", sans-serif;
                /* width: 794px */
            }
            
            .row {
                margin: 0;
            }
            
            .col-md-4 {
                width: 33.33333333%;
            }
            
            .col-md-6 {
                width: 50%;
                float: left;
            }
            
            .col-md-8 {
                width: 66.66666667%;
                float: left;
            }
            
            .col-md-4 {
                width: 33.33333333%;
                float: left;
            }
            
            
            /* .container {
                max-width: 3508px;
            } */
            
            .header {
                background-color: #0654b2;
            }
            
            .bill_of_heading {
                color: #fff;
                font-weight: 700;
                font-size: 17px;
            }
            
            .bill_of_paragraph {
                color: #fff;
                font-size: 14px;
                font-weight: 600;
            }
            
            .original_heading {
                font-weight: 600;
                font-size: 23px;
                text-transform: uppercase;
                color: #fff;
            }
            .col-md-6 {
                width: 50%;
                float: left;
            }
            .col-md-6 {
                width: 50%;
                float: left;
            }
            
            .shipper_heading {
                color: #0654b2;
                font-weight: 500;
            }
            .align-items-center {
                align-items: center!important;
            }
            .border-top {
                border-top: 1px  solid !important;
            }
            
            .border-left {
                border-left: 1px  solid !important;
            }
            
            .border-right {
                border-right: 1px  solid !important;
            }
            
            .border-bottom {
                border-bottom: 1px  solid !important;
            }
            
            .bill_logo {
                width: 100%;
                height: 198px;
                object-fit: cover;
            }
            
            .agent_details_heading {
                text-transform: uppercase;
            }
            
            .table_part_heading {
                color: #0654b2;
                font-weight: 500;
            }
            
            .table_part th {
                text-align: center;
                vertical-align: middle;
                border-left: 1px  solid !important;
            }
            
            .table_part th:first-child {
                border-left: 0 !important;
            }
            
            .table_part td {
                height: 440px;
                border-right: 1px  solid !important;
            }
            
            .table_bottom_text {
                color: #000;
                font-weight: 600;
                text-align: center;
                display: flex;
                align-items: end;
                height: 100%;
                justify-content: center;
            }
            
            .table {
                border-bottom-color: currentColor;
                padding: 0.5rem 0.5rem;
                background-color: var(--bs-table-bg);
                border-bottom-width: 1px;
                box-shadow: inset 0 0 0 9999px
                --bs-table-bg: transparent;
                --bs-table-accent-bg: transparent;
                --bs-table-striped-color: #212529;
                --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
                --bs-table-active-color: #212529;
                --bs-table-active-bg: rgba(0, 0, 0, 0.1);
                --bs-table-hover-color: #212529;
                --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
                vertical-align: top;
                border-color: #dee2e6;
            }
            .row>* {
                flex-shrink: 0;
                width: 100%;
                max-width: 100%;
                padding-right: calc(var(--bs-gutter-x) * .5);
                padding-left: calc(var(--bs-gutter-x) * .5);
                margin-top: var(--bs-gutter-y);
            }
            table {
                caption-side: bottom;
                border-collapse: collapse;
            }
            *, ::after, ::before {
                box-sizing: border-box;
            }
            user agent stylesheet
            table {
                display: table;
                border-collapse: separate;
                box-sizing: border-box;
                text-indent: initial;
                border-spacing: 2px;
                border-color: grey;
            }
            .row {
                --bs-gutter-x: 1.5rem;
                --bs-gutter-y: 0;
                display: flex;
                flex-wrap: wrap;
                margin-top: calc(var(--bs-gutter-y) * -1);
                margin-right: calc(var(--bs-gutter-x) * -.5);
                margin-left: calc(var(--bs-gutter-x) * -.5);
            }
       
            body {
                margin: 0;
                font-family: var(--bs-font-sans-serif);
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                background-color: #fff;
                -webkit-text-size-adjust: 100%;
                -webkit-tap-highlight-color: transparent;
            }
            :root {
                --bs-blue: #0d6efd;
                --bs-indigo: #6610f2;
                --bs-purple: #6f42c1;
                --bs-pink: #d63384;
                --bs-red: #dc3545;
                --bs-orange: #fd7e14;
                --bs-yellow: #ffc107;
                --bs-green: #198754;
                --bs-teal: #20c997;
                --bs-cyan: #0dcaf0;
                --bs-white: #fff;
                --bs-gray: #6c757d;
                --bs-gray-dark: #343a40;
                --bs-primary: #0d6efd;
                --bs-secondary: #6c757d;
                --bs-success: #198754;
                --bs-info: #0dcaf0;
                --bs-warning: #ffc107;
                --bs-danger: #dc3545;
                --bs-light: #f8f9fa;
                --bs-dark: #212529;
                --bs-font-sans-serif: system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans","Liberation Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
                --bs-font-monospace: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;
                --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
            }
            *, ::after, ::before {
                box-sizing: border-box;
            }
            *, ::after, ::before {
                box-sizing: border-box;
            }

            .table_freight_heading {
                color: #0654b2;
            }
            
            .border-left-black {
                border-left: 1px #000 solid !important;
            }
            
            .border-right-black {
                border-right: 1px #000 solid !important;
            }
            
            .border-top-black {
                border-top: 1px #000 solid !important;
            }
            
            .border-bottom-black {
                border-bottom: 1px #000 solid !important;
            }
            
            .shipper_heading_collect {
                text-transform: capitalize;
                text-align: center;
                height: 87px;
            }
            
            .total_heading {
                color: #0654b2;
            }
            
            .place_date_text {
                font-weight: 600;
            }
            .row {
                --bs-gutter-x: 1.5rem;
                --bs-gutter-y: 0;
                display: flex;
                flex-wrap: wrap;
                margin-top: calc(var(--bs-gutter-y) * -1);
                margin-right: calc(var(--bs-gutter-x) * -.5);
                margin-left: calc(var(--bs-gutter-x) * -.5);
                padding-right: calc(var(--bs-gutter-x) * .5);
                padding-left: calc(var(--bs-gutter-x) * .5);
                margin-top: var(--bs-gutter-y);
            }
            .freight_payable_at_heading {
                font-weight: 600;
            }
            
            .gradient_border_1 {
                border-bottom: 5px  solid;
            }
            .align-items-center {
                align-items: center!important;
            }
            .gradient_border_2 {
                border-bottom: 5px  solid;
            }
            
            .shipper_place_text {
                font-size: 15px;
            }
            </style>
            <title></title>
            
        </head>
        
        <body>
            <div class="container">
                <div class="header py-3">
                    <div class="row px-4">
                        <div class="col-md-6">
                            <div class="bill_of_heading">
                                BILL OF LADING
                            </div>
                            <div class="bill_of_paragraph">
                                FOR COMBINED TRANSPORT OR PORT TO PORT SHIPMENT
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="original_heading">
                                ORIGINAL
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-1 border-top">
                    <div class="col-md-6 pe-0" style=" ">
                        <div class="border-right">
                            <div class="shipper_heading" style="height: 150px;  ">Shipper</div>'.$invoice->shipper_address.'
                        </div>
                        <div class="border-top border-right" style="height: 150px;">
                            <div class="shipper_heading">Consignee</div>'.$invoice->consignee_address.'
                        </div>
                        <div class="border-top border-right" style="height: 150px;">
                            <div class="shipper_heading">
                                Notify Address (it is agreed that no responsibility shall attach to the carrier<br> or its Agents for failure to notify)
                            </div>'.$invoice->notify_address.'
                        </div>
                        <div class="row" style="height: 66px;">
                            <div class="col-md-6"style="">
                                <div class="shipper_heading">Vessel</div>'.$invoice->vessel.'
                            </div>
                            <div class="col-md-6 border-left">
                                <div class="shipper_heading">Voyage</div>'.$invoice->voyage.'
                            </div>
                        </div>
                        <div class="row border-top" style="height: 66px;">
                            <div class="col-md-6">
                                <div class="shipper_heading">Port of Loading</div>'.$invoice->port_of_loading.'
                            </div>
                            <div class="col-md-6 border-left">
                                <div class="shipper_heading">Port of Discharge</div>'.$invoice->port_of_dischange.'
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ps-0">
                        <div class="row">
                            <div class="col-md-6 shipper_heading" style="height: 50px;">
                                Bill / Lading Number :
                            </div>'.$invoice->bill_number.'
                            <div class="col-md-6 shipper_heading border-left" style="height: 50px;">
                                No of Original Bill of Lading
                            </div>'.$invoice->Bill_of_lading.'
                        </div>
                        <div class="row">
                            <div class="col-md-12 border-top">
                                <div class="">
                                    <div class="shipper_heading">
                                        <img src="image/logo.jpg" alt="" class="bill_logo">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 border-top" style="height: 201px;border-top: 1px  solid;">
                                <div class="">
                                    <div class="col-md-6 shipper_heading agent_details_heading" style="height: 50px;">
                                        AGENT DETAILS
                                    </div>'.$invoice->agent_address.'
                                </div>
                            </div>
                        </div>
                        <div class="row " style="height: 66px;">
                            <div class="col-md-6 border-left">
                                <div class="shipper_heading">Pre- carriage by</div>'.$invoice->pre_carriage_by.'
                            </div>
                            <div class="col-md-6 border-left">
                                <div class="shipper_heading">Pier or Port of Receipt</div>'.$invoice->port_of_receipt.'
                            </div>
                        </div>
                        <div class="row border-top" style="height: 66px;">
                            <div class="col-md-6 border-left">
                                <div class="shipper_heading">Place of Delivery / Final Destination</div>'.$invoice->delivery_place.'
                            </div>
                            <div class="col-md-6 border-left">
                                <div class="shipper_heading"> Country of Origin</div>'.$invoice->contry_origin.'
                            </div>
                        </div>
                    </div>
        
                </div>
                <div class="header py-2">
                    <div class="row px-4">
                        <div class="col-md-12 text-center">
                            <div class="bill_of_heading">
                                PARTICULARS FURNISHED BY THE SHIPPER - NOT CECKED BY CARRIER - CARRIER NOT RESPONSIBLE
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <table class="table table_part mb-0">
                        <thead>
                            <tr>
                                <th class="table_part_heading">Container No. / Seal No. <br> Marks & Numbers</th>
                                <th class="table_part_heading">Number of <br>containers or <br> Kind of packages</th>
                                <th class="table_part_heading">Kind of packages / description of goods</th>
                                <th class="table_part_heading">Gross Weight</th>
                                <th class="table_part_heading">Measurement</th>
                            </tr> 
                        </thead>
                        <tbody>';
                        
                        foreach($users->item as $user){
                          if($user->container_no != ""){
                            $HTMLContent .='<tr>
                             <td>'.$user->container_no.'</td>
                                <td>'.$user->container_package.'</td>
                                <td>'.$user->description_goods.'</td>
                                <td>'.$user->Gross_web.'</td>
                                <td>'.$user->Measurment.'</td>
                            </tr>';
                        }
                    }

                        $HTMLContent .='</tbody>
                    </table>
                    <div class="row table_freight_heading border-top-black px-0">
                        <div class="col-md-6 px-0 border-right-black gradient_border_1">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    Freight & Charges :
                                </div>
                                <div class="col-md-8 border-left-black">
                                    Cargo shall not delivered unless freight<br> and charges not paid
                                </div>
                            </div>
                            <div class="row border-top-black" style="height: 200px;">
                                <div class="col-md-4 shipper_heading_collect">
                                    Freight & Charges :
                                </div><span style="color:black;">'.$invoice->freight_charges.'</span>
                                <div class="col-md-4 shipper_heading_collect">
                                    prepaid
                                </div><span style="color:black;">'.$invoice->freight_charges.'</span>
                                <div class="col-md-4 shipper_heading_collect">
                                    Collect
                                </div>
                                <div class="row align-items-center px-0">
                                    <div class="col-md-2 pt-5 mt-3  pb-3">
                                        <div class="total_heading">Total:</div>
                                    </div>
                                    <div class="col-md-10 border-top ">
                                    </div>
                                </div>
                                <div class="border-top pt-3">
                                    <div class="freight_payable_at_heading">Freight Payable at:</div><span style="color:black;">'.$invoice->freight_payable_at.'</span>    
                                    <div class="row mt-5 px-0">
                                        <div class="col-md-6 px-0" >
                                            Mode of Shipment:
                                        </div><span style="color:black;">'.$invoice->mode_of_shipment.'</span>
                                        <div class="col-md-6 px-0">
                                            Shipped on board:
                                        </div><span style="color:black;">'.$invoice->shipped_on_board.'</span>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-md-6 py-2 gradient_border_2 shipper_place_text">
                            Received by the carrier the Goods as specified above in apparent good order and condition unless otherwise stated, to be transported to such place as agreed, authorised or permitted herein and subject to all the terms and conditions appearing on the front
                            and reverse of this bill of lading to which the merchant agrees by accepting the bill of lading, any local privileges and customs notwithstanding, The particulars given above stated by the shipper and the weight, measure, quantity,
                            condition, contents and value of the goods are unknown to the carrier. In WITNESS whereof one (1) original bill of lading has been signed if not otherwise stated above, the same being accomplished the other(s) if any, to be void, if
                            required by the carrier one (1) original bill of lading must be surrendered duly endorsed in exchange for the Goods or delivery order.
                            <div class="place_date_text mt-3">
                                Place and date of issue :
                            </div>'.$invoice->place_of_issue.','.$invoice->place_of_date.'
                            <div class="mt-5">
                                Signed as Agents of the Carrier / Agents
                            </div>
                            <div class="float-end mt-3">
                                Authorised Signatory
                            </div>
                        </div>
        
                    </div>
                </div>
            </div>
        
            <script src="js/popper.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
        </body>
        
        
        </html>';

        

        $filename = "Invoice_abc.pdf";
        $mpdf = new Mpdf(["autoScriptToLang" => true, "autoLangToFont" => true, 'mode' => 'utf-8', 'format' => 'A4', 'margin_left' => 5, 'margin_right' => 5, 'margin_top' => 5, 'margin_bottom' => 5, 'margin_header' => 0, 'margin_footer' => 0]);
        $mpdf->WriteHTML($HTMLContent);
        $mpdf->Output($filename,"I");
    }


}
