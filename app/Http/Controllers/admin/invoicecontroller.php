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
    public function index()
    {

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

        return view('admin.invoice.create');
    }
    public function list()
    {
        $action = "list";
        return view('admin.invoice.list', compact('action'));
    }

    public function allInvoicelist(Request $request)
    {
        if ($request->ajax()) {
            $columns = array(
                0 => 'id',
                1 => 'shipper_address',
                2 => 'consignee_address',
                3 => 'agent_address',
                4 => 'Bill_of_lading',
                5 => 'contry_origin',
                6 => 'Action',

            );

            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            if ($order == "id") {
                $order = "created_at";
                $dir = 'DESC';
            }

            $totalData = Invoice::count();
            $totalFiltered = $totalData;

            if (empty($request->input('search.value'))) {
                $Invoices = Invoice::offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = count($Invoices->toArray());
            } else {
                $search = $request->input('search.value');
                $Invoices = Invoice::where(function ($query) use ($search) {
                    $query->where('shipper_address', 'LIKE', "%{$search}%")
                        ->orWhere('consignee_address', 'LIKE', "%{$search}%")
                        ->orWhere('agent_address', 'LIKE', "%{$search}%")
                        ->orWhere('Bill_of_lading', 'LIKE', "%{$search}%")
                        ->orWhere('contry_origin', 'LIKE', "%{$search}%");
                })
                    ->offset($start)
                    ->limit($limit)
                    ->orderBy($order, $dir)
                    ->get();

                $totalFiltered = count($Invoices->toArray());
            }

            $data = array();

            if (!empty($Invoices)) {
                foreach ($Invoices as $Invoice) {


                    $action = '';
                    $action .= '<button id="printBtn" class="btn btn-gray text-warning btn-sm" data-id="' . $Invoice->id . '"><i class="fa fa-print" aria-hidden="true"></i></button>';
                    $action .= '<button id="editInvoiceBtn" class="btn btn-gray text-blue btn-sm" data-id="' . $Invoice->id . '"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
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
    {
        $messages = [
            'sname.required' => 'Please provide a Prefix For S Name',
            'cname.required' => 'please provide a prefix for C Name',
            'saddress.required' => 'Please provide  S address',
            'caddress.required' => 'please provide  C address',
            //'naddress.required' =>'Please provide N address',
            'aname.required' => 'please provide a prefix for A Name',
            'agaddress.required' => 'Please provide a address',
            'blading.required' => 'this field is required',
            'vessel.required' => 'this field is required',
            'voyage.required' => 'this field is required',
            'pol.required' => 'this field is required ',
            'pod.required' => 'this field is required ',
            'pody.required' => 'this field is required',
            'pcarriageby.required' => 'this field is required ',
            'por.required' => 'this field is required ',
            'cor.required' => 'this field is required',
            'containerno.required' => 'this field is required ',
            'countainerpackage.required' => 'this field is required',
            'description.required' => 'this field is required ',
            'gross.required' => 'this field is required ',
            'mesurment.required' => 'this field is required',
            'freight.required' => 'this field is required ',
            'poi.required' => 'this field is required ',
            'podi.required' => 'this field is required',
            'sonboard.required' => 'this field is required ',
            'mode.required' => 'this field is required',
            'fpat.required' => 'this field is required',
            'sname.required' => 'this field is required',
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
            // 'poi' => 'required',
            // 'podi' => 'required',
            // 'sonboard' => 'required',
            // 'mode' => 'required',
            'fpat' => 'required',


        ], $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'status' => 'failed']);
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

        foreach ($request->containerno as $key => $item) {

            //if($item != "" && $item != null){          
            $item_detail = new item;
            $item_detail->invoiceid = $request->invoice_id;
            $item_detail->container_no = $item;
            $item_detail->container_package = $request->countainerpackage[$key];
            $item_detail->description_goods = $request->description[$key];
            $item_detail->Gross_web  = $request->gross[$key];
            $item_detail->Measurment = $request->mesurment[$key];
            $item_detail->save();
            //}

        }
        //dd($company);




        // handle delete an employee ajax request



        // Invoice::whereId($id)->update($updateData);

        return response()->json([
            'status' => 200,
            'message' => 'Invoice updated Successfully.'
        ]);
    }
    public function edit($id)
    {

        //  $users = Invoice::leftjoin('item_details', 'item_details.invoiceid', '=', 'invoice_data.id')
        // ->get(['item_details.*', 'invoice_data.container_no']);

        //$invoice = Invoice::with('item')->first();
        //dd($invoice->item[0]->container_no);
        $action = "edit";
        $invoice = Invoice::where('id', $id)->first();
        //dd($invoice);
        return view('admin.invoice.list', compact('action', 'invoice'));
    }


    public function store(Request $request)
    {



        //dd($request->containerno);
        $messages = [
            'sname.required' => 'Please provide a Prefix For S Name',
            'cname.required' => 'please provide a prefix for C Name',
            'saddress.required' => 'Please provide  S address',
            'caddress.required' => 'please provide  C address',
            //'naddress.required' =>'Please provide N address',
            //'aname.required' =>'please provide a prefix for A Name',
            //'agaddress.required' =>'Please provide a address',
            // 'blading.required' =>'this field is required',
            'vessel.required' => 'this field is required',
            'voyage.required' => 'this field is required',
            'pol.required' => 'this field is required ',
            'pod.required' => 'this field is required ',
            'pody.required' => 'this field is required',
            //'pcarriageby.required' =>'this field is required ',
            //'por.required' =>'this field is required ', 
            //'cor.required' =>'this field is required', 
            'containerno.required' => 'this field is required ',
            'countainerpackage.required' => 'this field is required',
            'description.required' => 'this field is required ',
            'gross.required' => 'this field is required ',
            'mesurment.required' => 'this field is required',
            'freight.required' => 'this field is required ',
            'poi.required' => 'this field is required ',
            'podi.required' => 'this field is required',
            'sonboard.required' => 'this field is required ',
            'mode.required' => 'this field is required',
            //'fpat.required' =>'this field is required',
            'sname.required' => 'this field is required',
        ];
        // dd($request->all());
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
            //     'containerno' => 'required',
            //    'countainerpackage' => 'required',
            //     'description' => 'required',
            //    'gross' => 'required',
            //    'mesurment' => 'required',
            'freight' => 'required',
            // 'poi' => 'required',
            // 'podi' => 'required',
            // 'sonboard' => 'required',
            // 'mode' => 'required',
            //'fpat' => 'required',


        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'status' => 'failed']);
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
        $responsetoken = json_decode($response, true);
        //dd($responsetoken);
        $accessToken = $responsetoken['access_token'];


        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://books.zoho.in/api/v3/salesorders/938173000000186007?organization_id=60015618450',
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
        $response1 = json_decode($response, true);
        //dd($response1['salesorder']['custom_fields']);
        $var  = "";
        $var2  = "";
        $var3  = "";
        $var4  = "";
        $var5  = "";

        foreach ($response1['salesorder']['custom_fields'] as $key => $item) {
            // dump($item['label']);

            $item['label'];
            if ($item['label'] == "Final Destination") {
                $var = $item['value'];
            }
            if ($item['label'] == "Vessel / Voy") {
                $var2 = $item['value'];
                //dd($var2);
            }

            if ($item['label'] == "Vessel / Voy") {
                $var3 = $item['value'];
            }

            if ($item['label'] == "POL") {
                $var4 = $item['value'];
                // dump("value 1");

            }

            if ($item['label'] == "POD") {
                $var5 = $item['value'];
                // dump("value 2");
            }
        }
        //dd($var2);
        $lastUsedSerialNumber = invoice::query()->orderByDesc('id')->first();
        if ($lastUsedSerialNumber) {
            $last_id =  $lastUsedSerialNumber->id + 1;
        } else {
            $last_id =   1;
        }

        $invoice = new invoice;
        // $lastInvoiceID = $invoice->orderBy('id', DESC)->pluck('id')->first();
        // $newInvoiceID = $lastInvoiceID + 1;

        $invoice->shipper_name = $request->input('sname');
        $invoice->shipper_address = $request->input('saddress');
        $invoice->consignee_name = $request->input('cname');
        $invoice->consignee_address = $request->input('caddress');
        $invoice->notify_address = $request->input('naddress');
        // $invoice->agent_name = $request->input('aname');
        //$invoice->agent_address = $response1['Vessel'];
        $invoice->bill_number  = "PSLPKLJPR" . date('Y-m-d') .   $last_id;
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

        if ($data['freight'] == "prepaid") {
            $invoice->freight_payable_at = $var4;
        } else {
            $invoice->freight_payable_at = $var5;
        }
        $invoice->place_of_issue = $request->input('poi');
        $invoice->place_of_date  = $request->input('podi');
        //$invoice->shipped_on_board = $var5;
        $invoice->mode_of_shipment    = "FCL/FCL";

        $invoice->save();
        $invoice->id;
        //dd($invoice);
        foreach ($request->containerno as $key => $item) {

            //if($item != "" && $item != null){          
            $item_detail = new item;
            $item_detail->invoiceid = $invoice->id;
            $item_detail->container_no = $item;
            $item_detail->container_package = $request->countainerpackage[$key];
            $item_detail->description_goods = $request->description[$key];
            $item_detail->Gross_web  = $request->gross[$key];
            $item_detail->Measurment = $request->mesurment[$key];
            $item_detail->save();
            // }

        }

        return response()->json([
            'status' => 200,
            'message' => 'Invoice Added Successfully.'
        ]);
    }




    public function generate_pdf($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $settings = Setting::find(1);

        $image = '';
        if (isset($settings->company_logo)) {
            $image = '<img src="' . url('public/images/company/' . $settings->company_logo) . '" alt="Logo" width="100px" height="100px">';
        }


        $HTMLContent = '<html>

        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="css/style.css">';

        $HTMLContent .= '<style type="text/css">

            @page {
                page-break-after: avoid;
            }
            body {
                font-family: "Roboto", sans-serif;
                page-break-after: always;
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
            
            .shipper_heading {
                color: #0654b2;
                font-weight: 500;
            }
            .align-items-center {
                align-items: center!important;
            }
            .border-top {
                border-top: 1px  solid #0654b2;
            }
            
            .border-left {
                border-left: 1px  solid #0654b2;
            }
            
            .border-right {
                border-right: 1px  solid #0654b2;
            }
            
            .border-bottom {
                border-bottom: 1px  solid #0654b2;
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
                border-left: 1px  solid #0654b2 !important;
            }
            
            .table_part th:first-child {
                border-left: 0 !important;
            }
            
            .table_part td {
                border-right: 1px  solid #0654b2 !important;
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
                --bs-table-bg: transparent;
                --bs-table-accent-bg: transparent;
                --bs-table-striped-color: #212529;
                --bs-table-striped-bg: rgba(0, 0, 0, 0.05);
                --bs-table-active-color: #212529;
                --bs-table-active-bg: rgba(0, 0, 0, 0.1);
                --bs-table-hover-color: #212529;
                --bs-table-hover-bg: rgba(0, 0, 0, 0.075);
                width: 100%;
             
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
                border-color: #;
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
                border-left: 1px  solid #0654b2 !important;
            }
            
            .border-right-black {
                border-right: 1px solid  #0654b2 !important;
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
        
        <body style="font-size:11px;">
            <div class="container pagebreak">
                <div class="header py-3">
                    <div class="row px-4">
                        <div class="col-md-6">
                            <div class="bill_of_heading" style="padding-left:15px;font-size:15px;font-weight:bold;">
                                BILL OF LADING
                            </div>
                            <div class="bill_of_paragraph" style="padding-left:15px;font-size:12px;">
                                FOR COMBINED TRANSPORT OR PORT TO PORT SHIPMENT
                            </div>
                        </div>
                        <div class="col-md-6 text-end" style="text-align:right;">
                            <div class="original_heading" style="padding-right:15px;font-size:16px;font-weight:bold;"> 
                                ORIGINAL
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-1 border-top">
                    <div class="col-md-6 pe-0" style="">
                        <div class="border-right">
                            <div class="shipper_heading" style="">Shipper</div>
                            <div style="">' . $invoice->shipper_address . '</div>
                        </div>
                        <div class="border-top border-right" style="height: 150px;">
                            <div class="shipper_heading" style="">Consignee</div>
                            <div style="">' . $invoice->consignee_address . '</div>
                        </div>
                        <div class="border-top border-right" style="height: 150px;">
                            <div class="shipper_heading" style="">
                                Notify Address (it is agreed that no responsibility shall attach to the carrier<br> or its Agents for failure to notify)
                            </div>
                            <div style="">' . $invoice->notify_address . '</div>
                        </div>


                       
                        
                    </div>
                    <div class="col-md-6 ps-0">
                        <div class="row" style="display:flex;width:100%;">
                            <table>
                            <tr>
                                <td class="shipper_heading" style="padding-left:10px;width:50%;">Bill / Lading Number :</td>
                                <td class="shipper_heading" style="border-left:1px solid #0654b2;padding-left:10px;width:50%;">No of Original Bill of Lading :</td>
                            </tr>
                            <tr>
                                <td class="" style="padding-left:10px;">' . $invoice->bill_number . '</td>
                                <td style="border-left:1px solid #0654b2;padding-left:10px;">' . $invoice->Bill_of_lading . '</td>
                            </tr>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-12 border-top">
                                <div class="">
                                    <div class="shipper_heading" style="height:211px;">
                                        <img src="image/logo.jpg" alt="" class="bill_logo">
                                    </div>
                                </div>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 border-top">
                                <div class="">
                                    <div class="col-md-6  agent_details_heading" style="height:89px;padding-left:10px;">
                                        <div class="shipper_heading">AGENT DETAILS</div>
                                        <div>
                                            ' . $invoice->agent_address . '
                                        </div>
                                    </div>
                                 
                                </div>
                            </div>
                        </div>
                       
                    </div>
        
                </div>
                <table style="border-top:1px solid #0654b2;">
                    <tr>
                        <td class="shipper_heading" style="width:50%;padding-top:5px;">Vessel</td>
                        <td class="shipper_heading" style="width:50%;border-left:1px solid #0654b2;padding-bottom:5px;padding-left:10px;">Voyage</td>
                        <td class="shipper_heading" style="padding-left:10px;width:50%;border-left:1px solid #0654b2;">Pre-carriage by </td>
                        <td class="shipper_heading" style="border-left:1px solid #0654b2;padding-left:10px;width:50%;">Pier or Port of Receipt</td>
                    
                    </tr>
                    <tr>
                        <td class="" style="width:50%;padding-top:5px;">' . $invoice->voyage . '</td>  
                        <td  style="width:50%;border-left:1px solid #0654b2;padding-left:10px;padding-bottom:5px;">' . $invoice->vessel . '</td>
                        <td class="" style="padding-left:10px;width:50%;border-left:1px solid #0654b2;">' . $invoice->bill_number . '</td>
                        <td style="border-left:1px solid #0654b2;padding-left:10px;width:50%;">' . $invoice->Bill_of_lading . '</td>
                    </tr>
                    <tr style="">
                        <td class="shipper_heading" style="width:50%;border-top:1px solid #0654b2;padding-top:5px;">Port of Loading</td>
                        <td class="shipper_heading" style="width:50%;border-left:1px solid #0654b2;padding-left:10px;border-top:1px solid #0654b2;padding-bottom:5px;">Port of Discharge</td>
                        <td class="shipper_heading" style="padding-left:10px;width:50%;border-top:1px solid #0654b2;border-left:1px solid #0654b2;">Place of Delivery / Final Destination</td>
                        <td class="shipper_heading" style="border-left:1px solid bl#0654b2ack;padding-left:10px;width:50%;border-top:1px solid #0654b2;"> Country of Origin</td>
                    </tr>
                    <tr style="border-top:1px solid #0654b2;">
                        <td class="" style="width:50%;padding-top:5px;">' . $invoice->port_of_loading . '</td>
                        <td style="border-left:1px solid #0654b2;width:50%;padding-left:10px;padding-bottom:5px;">' . $invoice->port_of_dischange . '</td>
                        <td class="" style="padding-left:10px;width:50%;border-left:1px solid #0654b2;">' . $invoice->delivery_place . '</td>
                        <td style="border-left:1px solid #0654b2;padding-left:10px;width:50%;">' . $invoice->contry_origin . '</td>
                    </tr>
                </table>
                <div class="header py-2" style="">
                    <div class="row px-4">
                        <div class="col-md-12 text-center">
                            <div class="bill_of_heading;" style="color:white;text-align:center;font-weight:bold;padding:6px 0;">
                                PARTICULARS FURNISHED BY THE SHIPPER - NOT CECKED BY CARRIER - CARRIER NOT RESPONSIBLE
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row ">
                    <table class="table table_part" style="margin-bottom:0px;">
                        <thead>
                            <tr style="font-size:12px;">
                                <th class="table_part_heading" style="border-bottom:1px solid #0654b2;">Container No. / Seal No. <br> Marks & Numbers</th>
                                <th class="table_part_heading" style="border-bottom:1px solid #0654b2;">Number of <br>containers or <br> Kind of packages</th>
                                <th class="table_part_heading" style="border-bottom:1px solid #0654b2;">Kind of packages / description of goods</th>
                                <th class="table_part_heading" style="border-bottom:1px solid #0654b2;">Gross Weight</th>
                                <th class="table_part_heading" style="border-bottom:1px solid #0654b2;">Measurement</th>
                            </tr> 
                        </thead>
                        <tbody style="border-bottom:1px solid #0654b2;">
                            <tr>
                                <td>
                                ' . $invoice->container_no . '
                                ' . $invoice->container_package . '
                                ' . $invoice->description_goods . '
                                ' . $invoice->Gross_web . '
                                ' . $invoice->Measurment . '
                                </td>
                               
                           </tr>
                           <tr style="border-bottom:1px solid #0654b2;">
                                <td style="">
                                            
                                </td>
                                <td style="">
                                    
                                </td>
                                <td class="" style="text-align:center;">
                                    SHIPPERS LOAD COUNT AND SEAL
                                </td>
                                <td style=""></td>
                                <td style="border-right:0;"></td>
                           </tr>
                        </tbody>
                    </table>
                    
                    <div class="row table_freight_heading  px-0">
                            <div class="col-md-6 border-right-black gradient_border_1" style="width:42%;border-bottom:5px solid #40475f;height:280px;">
                                <div class="row align-items-center">
                                    <div class="col-md-6" style="width:35%;float:left;border-top:1px solid #0654b2;">
                                        Freight & Charges :
                                    </div>
                                    <div class="col-md-6 border-left-black" style="width:64%;">
                                        <div style="border-top:1px solid #0654b2;padding-left:10px;padding-right:10px;">
                                            Cargo shall not delivered unless freight and charges not paid
                                        </div>
                                    </div>
                                </div>
                                <div class="row border-top-black" style="height: 200px;border-top:1px solid #0654b2;">
                                    <div class="col-md-4 shipper_heading_collect">
                                        Freight & Charges :
                                    </div>
                                    <div class="col-md-4 shipper_heading_collect">
                                        prepaid
                                    </div>
                                    <div class="col-md-4 shipper_heading_collect">
                                        Collect
                                    </div>
                                    <div class="row align-items-center px-0">
                                        <div class="col-md-2" style="width:20%;float:left;">
                                            <div class="total_heading">Total:</div>
                                        </div>
                                        <div class="col-md-10" style="width:80%;border-top:1px solid #0654b2;">
                                            <div class=""> </div>
                                        </div>
                                    </div>
                                    <div class="border-top pt-3">
                                        <div class="freight_payable_at_heading col-md-6">Freight Payable at:</div>
                                        <div class="col-md-6">' . $invoice->freight_payable_at . '</div>
                                        <div class="shipper_heading col-md-6" style="width:50%;margin-top:10px;">Mode of Shipment:</div>
                                        <div class="shipper_heading col-md-6" style="width:50%;margin-top:10px;">Shipped on board:</div>
                                        <div class="" style="color:black;">FCL/FCL</div>
                                        <div></div>
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-6 gradient_border_2 shipper_place_text" style="width:57.5%;border-bottom:5px solid #0654b2;height:280px;">
                                <div style="font-size:11px;padding-left:15px;border-top:1px solid #0654b2;padding-top:10px;">
                                    Received by the carrier the Goods as specified above in apparent good order and condition unless otherwise stated, to be transported to such place as agreed, authorised or permitted herein and subject to all the terms and conditions appearing on the front
                                    and reverse of this bill of lading to which the merchant agrees by accepting the bill of lading, any local privileges and customs notwithstanding, The particulars given above stated by the shipper and the weight, measure, quantity,
                                    condition, contents and value of the goods are unknown to the carrier. In WITNESS whereof one (1) original bill of lading has been signed if not otherwise stated above, the same being accomplished the other(s) if any, to be void, if
                                    required by the carrier one (1) original bill of lading must be surrendered duly endorsed in exchange for the Goods or delivery order.
                                    <div class="place_date_text mt-3">
                                    <div style="font-weight:bold;"> Place and date of issue :</div>
                                    </div>' . $invoice->place_of_issue . '' . $invoice->place_of_date . '
                                    <div class="mt-5" style="text-align:left;margin-top:15px;">
                                        <div>Signed as Agents of the Carrier / Agents</div>
                                    </div>
                                    <div class="float-end mt-3 col-md-12" style="text-align:right;margin-top:15px;">
                                        Authorised Signatory
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        
        </body>
        
        
        </html>';



        $filename = "Invoice_abc.pdf";
        $mpdf = new Mpdf(["autoScriptToLang" => true, "autoLangToFont" => true, 'mode' => 'utf-8', 'format' => 'A4', 'margin_left' => 5, 'margin_right' => 5, 'margin_top' => 5, 'margin_bottom' => 5, 'margin_header' => 0, 'margin_footer' => 0]);
        $mpdf->WriteHTML($HTMLContent);
        $mpdf->Output($filename, "I");
    }
}
