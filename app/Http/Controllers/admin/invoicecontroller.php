<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\session;
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
     public function accesstoken(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://accounts.zoho.in/oauth/v2/token?&refresh_token=1000.ff46e1048cddd9a9b86ba4abe804c4ba.a923be1535a22a81dde6b032a96baf63&client_id=1000.F998L05TJ3JSGW0RE7NWAJZ7WYB9YV&client_secret=492623e95cbb29a2953d664489c565b018e088f20e&grant_type=refresh_token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Cookie: 6e73717622=3bcf233c3836eb7934b6f3edc257f951; _zcsr_tmp=83aca140-5259-497f-a2b5-da5bcf6fa5c4; iamcsr=83aca140-5259-497f-a2b5-da5bcf6fa5c4'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response,true);
       
        $request->session()->put('access_token',$data['access_token']);

    }
    public function index(Request $request ,$billno){
        
       $value = $request->session()->get('access_token');
       
        $bill = $billno;
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
            'Authorization: Zoho-oauthtoken '.$value,
            'Content-Type: application/json',
            'Cookie: BuildCookie_60015618450=1; 54900d29bf=6546c601cb473cceb7511983f377761e; JSESSIONID=4165A10F45BF9DC696D33277DEC05C7F; _zcsr_tmp=0b60f2e4-8676-4c08-b641-640f8c42997f; zbcscook=0b60f2e4-8676-4c08-b641-640f8c42997f'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response1 = json_decode($response,true);
        
        if($response1['code'] == 14 || $response1['code'] == 57)
        {
            $this->accesstoken($request);
            $this->index($request ,$billno);
        }
        else
        {   
            if($response1['message'] == "success")
            {   
                return view('admin.invoice.create',compact('billno'));
            }
            else
            {
                echo $response1['message'];
            }
        }

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
            'sname.required' => 'this field is required',
            'cname.required' => 'this field is required',
            'saddress.required' => 'Please provide  S address',
            'caddress.required' => 'please provide  C address',
            //'naddress.required' =>'Please provide N address',
            'aname.required' => 'this field is required',
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
            // 'pcarriageby' => 'required',
            // 'por' => 'required',
            // 'cor' => 'required',
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
            $item_detail->container_type = $request->containertype[$key];
            $item_detail->seal_no = $request->seal[$key];
            $item_detail->Gross_web  = $request->gross[$key];
            $item_detail->Measurment = $request->mesurment[$key];
            $item_detail->net_weight = $request->netwt[$key];
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

    public function add_row_item(Request $request){
        $language = $request->language;
        $next_item = $request->total_item + 1;

       // $products = invoice::where('estatus',1)->get();
   

    $html = '       
    <div class="col-lg-2 mt-4">

            
    <div class="form-group">
      <label for="contain1" class="form-label">Container no </label>
      <input type="text" class="form-control" name="containerno[]" id="contain1" required>
      <label id="containerno-error" class="error invalid-feedback animated fadeInDown" for="containerno"></label>
    </div>



    <div class="form-group">
      <label for="containg" class="form-label">Number of packages</label>
      <input type="number" class="form-control" name="countainerpackage[]" id="containg" required>
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

<input type="number" class="form-control" name="gross[]" id="containf" required>
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

      <input type="number" class="form-control" name="mesurment[]" id="containd" required>
      <label id="mesurment-error" class="error invalid-feedback animated fadeInDown" for="mesurment"></label>
    </div>
  </div>
  <div class="col-md-4 mt-2">
    <div class="form-group">
      <label for="des" class="form-label">Marks & numbers/Kind of packages/description of goods</label>
      <textarea rows="5" class="form-control summernote" name="description[]" cols="45" id="des" name="comment" required>
      </textarea>
      <label id="description-error" class="error invalid-feedback animated fadeInDown" for="description"></label>
      
    </div>
  </div>
</div>';

        return ['html' => $html, 'next_item' => $next_item];
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
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://accounts.zoho.in/oauth/v2/token?refresh_token=1000.ff46e1048cddd9a9b86ba4abe804c4ba.a923be1535a22a81dde6b032a96baf63&client_id=1000.F998L05TJ3JSGW0RE7NWAJZ7WYB9YV&client_secret=492623e95cbb29a2953d664489c565b018e088f20e&grant_type=refresh_token',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_HTTPHEADER => array(
        //         'Cookie: 6e73717622=4440853cd702ab2a51402c119608ee85; _zcsr_tmp=4e12e670-8911-4bb6-81ae-1112e8bef96d; iamcsr=4e12e670-8911-4bb6-81ae-1112e8bef96d'
        //     ),
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);
        // $responsetoken = json_decode($response, true);
        // //dd($responsetoken);
        // $accessToken = $responsetoken['access_token'];

        $value = $request->session()->get('access_token');
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
                'Authorization: Zoho-oauthtoken '.$value,
                'Content-Type: application/json',
                'Cookie: BuildCookie_60015618450=1; 54900d29bf=6546c601cb473cceb7511983f377761e; JSESSIONID=4165A10F45BF9DC696D33277DEC05C7F; _zcsr_tmp=0b60f2e4-8676-4c08-b641-640f8c42997f; zbcscook=0b60f2e4-8676-4c08-b641-640f8c42997f'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response1 = json_decode($response, true);
       
        
        if($response1['code'] == 14 || $response1['code'] == 57)
        {
            $this->accesstoken($request);
            $this->store($request);
        }
        //dd($response1['salesorder']['custom_fields']);
        $var  = "";
        $var2  = "";
        $var3  = "";
        $var4  = "";
        $var5  = "";

        foreach ($response1['salesorder']['custom_fields'] as $key => $item) {
            //dump($item['label']);

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
        $invoice->check_notify_address = $request->input('check_notify');
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
            $item_detail->container_type = $request->containertype[$key];
            $item_detail->seal_no = $request->seal[$key];
            $item_detail->Gross_web  = $request->gross[$key];
            $item_detail->Measurment = $request->mesurment[$key];
            $item_detail->net_weight = $request->netwt[$key];
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
        $users = Invoice::with('item')->where('id',$id)->first();
        $invoice = Invoice::where('id', $id)->first();
        $settings = Setting::find(1);

        $image = '';
        if (isset($settings->company_logo)) {
            $image = '<img src="' . url('public/images/company/' . $settings->company_logo) . '" alt="Logo" width="100px" height="100px">';
        }

        $shipper_count_address = substr_count($invoice->shipper_address,"</p>");
        //$shipper_address = trim($invoice->shipper_address,"<p></p>");
        $shipper_address_array = explode('</p>',$invoice->shipper_address);

        $consignee_count_address = substr_count($invoice->consignee_address,"</p>");
        $consignee_address_array = explode('</p>',$invoice->consignee_address);

        $notify_count_address = substr_count($invoice->notify_address,"</p>");
        $notify_address_array = explode('</p>',$invoice->notify_address);

        $agent_count_address = substr_count($invoice->agent_address,"</p>");
        $agent_address_array = explode('</p>',$invoice->agent_address);

        $item_row = 24;
        $shipper_row = 2;
        $consignee_row = 2;
        $notify_row = 2;
        $agent_row = 4;

        
        
        $HTMLContent = '<html>

        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" href="css/style.css">';

            $HTMLContent .= '<style type="text/css">
           
            @page {
                margin: 0;  /* this affects the margin in the printer settings */
            }

            @media print {
                .pagebreak { page-break-before: always; } /* page-break-after works, as well */
            }

            @page {
                page-break-after: avoid;
            }
            
            body {
                font-family: "Roboto", sans-serif;
                page-break-after: always;
            }
            
            .row {
                margin: 0 !important;
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
            
            .header_s {
                background-color: #00306A;
                margin-left: 0;
            }
            
            .bill_of_heading {
                color: #fff;
                font-weight: 700;
                font-size: 12px;
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
                color: #00306A;
                font-weight: 500;
                padding: 2px 12px;
                font-size: 12px;
            }
            
            .align-items-center {
                align-items: center!important;
            }
            
            .border-top {
                border-top: 1px solid #00306A;
            }
            
            .border-left {
                border-left: 1px solid #00306A;
            }
            
            .border-right {
                border-right: 1px solid #00306A;
            }
            
            .border-bottom {
                border-bottom: 1px solid #00306A;
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
                color: #00306A;
                font-weight: 500;
            }
            
            .table_part th {
                text-align: center;
                vertical-align: middle;
                border-left: 1px solid #00306A !important;
            }
            
            .table_part th:first-child {
                border-left: 0 !important;
            }
            
            .table_part td {
                border-right: 1px solid #00306A !important;
                font-size: 12px;
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
            
            p {
                margin-bottom: 0;
                margin-top: 0;
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
            
            *,
            ::after,
            ::before {
                box-sizing: border-box;
            }
            
            user agent stylesheet table {
                display: table;
                border-collapse: separate;
                box-sizing: border-box;
                text-indent: initial;
                border-spacing: 2px;
                border-color: #000000;
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
                --bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
                --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
            }
            
            *,
            ::after,
            ::before {
                box-sizing: border-box;
            }
            
            *,
            ::after,
            ::before {
                box-sizing: border-box;
            }
            
            .table_freight_heading {
                color: #00306A;
                font-size: 12px;
            }
            
            .border-left-black {
                border-left: 1px solid #00306A !important;
            }
            
            .border-right-black {
                border-right: 1px solid #00306A !important;
            }
            
            .shipper_heading_collect {
                text-transform: capitalize;
                text-align: center;
                height: 87px;
            }
            
            .total_heading {
                color: #00306A;
                padding: 2px 5px;
            }
            
            .place_date_text {
                font-weight: 600;
            }
            
            .freight_payable_at_heading {
                font-weight: 600;
                padding: 2px 5px;
                font-size: 12px;
            }
            
            .gradient_border_1 {
                border-bottom: 5px solid;
            }
            
            .align-items-center {
                align-items: center!important;
            }
            
            .gradient_border_2 {
                border-bottom: 5px solid;
            }
            
            .shipper_place_text {
                font-size: 15px;
                padding: 0;
            }
            
            .itemTable tr td,
            .itemTable tr th {
                padding: 3px 5px;
            }
            
            .text-val {
                padding: 0 0 5px 12px;
                font-size: 12px;
            }
            
            .bill-comman-class {
                font-size: 12px;
            }
            .address-box p{
                word-break: break-all;
            }
                </style>
                <title></title>
                
        </head>
        
        <body style="font-size:11px;">
        <div class="container ">
        <div class="header_s py-3" style="background: #00306A; margin-left: 0;">
            <div class="row ">
                <div class="col-md-6 px-0">
                    <div class="bill_of_heading" style="padding-left:15px;font-size:15px;font-weight:bold;">
                        BILL OF LADING
                    </div>
                    <div class="bill_of_paragraph" style="padding-left:15px;font-size:12px;">
                        FOR COMBINED TRANSPORT OR PORT TO PORT SHIPMENT
                    </div>
                </div>
                <div class="col-md-6 text-end px-0" style="text-align:right;">
                    <div class="original_heading" style="padding-right:15px;font-size:16px;font-weight:bold;">
                        ORIGINAL
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-1 border-top">
            <div class="col-md-6 px-0">
                <div class="border-right" style="height: 105px;">
                    <div class="shipper_heading">Shipper</div>
                    <div class="text-val address-box">';
                    $showShipperAddr = 0; 
                    
                    $shipper_address_array_new = array(); 
                                
                    foreach($shipper_address_array as $shipper_address){
                        if($shipper_address != ""){
                            $addresscatcount =  round(strlen($shipper_address) / 70);
                        
                            if($addresscatcount == 0.0 || $addresscatcount == 1.0){
                              
                                $shipper_address_array_new[] = $shipper_address;
                            }else{
                                $result = substr($shipper_address, 70);
                               
                                $shipper_address_array_new[] = $result;
                            }
                        }
                    }
                    
                   
                    $shipper_count_address = count($shipper_address_array_new);
                    $HTMLContent .= ' '.$invoice->shipper_name.' ';
                    if($shipper_count_address > $shipper_row + 1){
                        for($x = 0; $x < $shipper_row; $x++){
                            $HTMLContent .= ' '.   $shipper_address_array_new[$x] .'';
                        }
                        $HTMLContent .= '<p><b>*</b></p>';
                    }else{
                        
                        $shipper_row = $shipper_row + 1;
                        $showShipperAddr = 1; 
                        for($x = 0; $x < $shipper_count_address; $x++){
                            $HTMLContent .= ' '.   $shipper_address_array_new[$x] .'';
                        }
                    }    
                    $HTMLContent .= '</div>

                    

                </div>
                <div class="border-top border-right" style="height: 105px;">
                    <div class="shipper_heading">Consignee</div>
                    <div class="text-val">';
                    $showConsigneeAddr = 0;

                    $consignee_address_array_new = array(); 
                                
                    foreach($consignee_address_array as $consignee_address){
                        if($consignee_address != ""){
                            $addresscatcount =  round(strlen($consignee_address) / 70);
                        
                            if($addresscatcount == 0.0 || $addresscatcount == 1.0){
                              
                                $consignee_address_array_new[] = $consignee_address;
                            }else{
                                $result = substr($consignee_address, 70);
                               
                                $consignee_address_array_new[] = $result;
                            }
                        }
                    }
                    
                   
                    $consignee_count_address = count($consignee_address_array_new);
                    $HTMLContent .= ' '.$invoice->consignee_name.' ';
                    if($consignee_count_address > $consignee_row + 1){
                        for($x = 0; $x < $consignee_row; $x++){
                            $HTMLContent .= ' '.   $consignee_address_array_new[$x] .'';
                        }
                        $HTMLContent .= '<p><b>**</b></p>';
                    }else{
                        $consignee_row = $consignee_row + 1;
                        $showConsigneeAddr = 1;
                        for($x = 0; $x < $consignee_count_address; $x++){
                            $HTMLContent .= ' '.   $consignee_address_array_new[$x] .'';
                        }
                    }
                    
                    
                    
                $HTMLContent .= '</div>
                </div>
                <div class="border-top border-right" style="height: 110px;">
                    <div class="shipper_heading">
                        Notify Address (it is agreed that no responsibility shall attach to the carrier or its Agents for failure to notify)
                    </div>
                    <div class="text-val">';
                    $showNotifyAddr = 0;

                    $notify_address_array_new = array(); 
                                
                    foreach($notify_address_array as $notify_address){
                        if($notify_address != ""){
                            $addresscatcount =  round(strlen($notify_address) / 70);
                        
                            if($addresscatcount == 0.0 || $addresscatcount == 1.0){
                              
                                $notify_address_array_new[] = $notify_address;
                            }else{
                                $result = substr($notify_address, 70);
                               
                                $notify_address_array_new[] = $result;
                            }
                        }
                    }
                    
                   
                    $notify_count_address = count($notify_address_array_new);
                    
                    if($invoice->check_notify_address != 2){
                        if($notify_count_address > $notify_row  + 1){
                            for($x = 0; $x < $notify_row; $x++){
                                $HTMLContent .= ' '.   $notify_address_array[$x] .'';
                            }
                            $HTMLContent .= '<p><b>***</b></p>';
                        }else{
                            $notify_row = $notify_row + 1;
                            $showNotifyAddr = 1;
                            for($x = 0; $x < $notify_count_address; $x++){
                                $HTMLContent .= ' '.   $notify_address_array[$x] .'';
                            }
                        }
                    }else{
                        $HTMLContent .= ' Same As Consignee';
                    }    
                    $HTMLContent .= '</div>
                </div>
            </div>
            <div class="col-md-6 px-0">
                <div class="row" style="display:flex;width:100%;">
                    <table>
                        <tr>
                            <td class="shipper_heading" style="padding-left:10px;width:50%;">Bill / Lading Number:</td>
                            <td class="shipper_heading" style="border-left:1px solid #00306A;padding-left:10px;width:50%;">No of Original Bill of Lading:</td>
                        </tr>
                        <tr>
                            <td class="bill-comman-class" style="padding-left:10px;">' . $invoice->bill_number . '</td>
                            <td class="bill-comman-class" style="border-left:1px solid #00306A;padding-left:10px;"></td>
                        </tr>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-12 border-top">
                        <div class="">
                            <div class="shipper_heading" style="height:150px;">
                                <img src="https://matoresell.com/zoho-invoice/public/images/invoice/logo_new.jpg" alt="" style="height:150px;" class="bill_logo"> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 border-top border-top px-0">
                        <div class="col-md-12 px-0 agent_details_heading" style="height:131px;">
                            <div class="shipper_heading">AGENT DETAILS</div>
                            <div class="text-val">  ';
                            $showAgentAddr = 0;

                            $agent_address_array_new = array(); 
                                        
                            foreach($agent_address_array as $agent_address){
                                if($agent_address != ""){
                                    $addresscatcount =  round(strlen($agent_address) / 70);
                                
                                    if($addresscatcount == 0.0 || $addresscatcount == 1.0){
                                      
                                        $agent_address_array_new[] = $agent_address;
                                    }else{
                                        $result = substr($agent_address, 70);
                                       
                                        $agent_address_array_new[] = $result;
                                    }
                                }
                            }
                            
                           
                            $agent_count_address = count($agent_address_array_new);
                            
                            $HTMLContent .= ' '.$invoice->agent_name.' ';
                            if($agent_count_address > $agent_row  + 1){
                                for($x = 0; $x < $agent_row; $x++){
                                    $HTMLContent .= ' '.   $agent_address_array[$x] .'';
                                }
                                $HTMLContent .= '<p><b>****</b></p>';
                            }else{
                                $agent_row = $agent_row + 1;
                                $showAgentAddr = 1;
                                for($x = 0; $x < $agent_count_address; $x++){
                                    $HTMLContent .= ' '.   $agent_address_array[$x] .'';
                                }
                            }  
                             $HTMLContent .= '</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <table style="border-top:1px solid #00306A;width: 100%;">
            <tr>
                <td class="shipper_heading" style="width:25%;">Vessel</td>
                <td class="shipper_heading" style="width:25%;border-left:1px solid #00306A;padding-bottom:5px;">Voyage</td>
                <td class="shipper_heading" style="width:25%;border-left:1px solid #00306A;">Pre-carriage by </td>
                <td class="shipper_heading" style="border-left:1px solid #00306A;width:25%;">Pier or Port of Receipt</td>
            </tr>
            <tr>
                <td class="text-val" style="width:25%;">' . $invoice->voyage . '</td>
                <td class="text-val" style="width:25%; border-left:1px solid #00306A;">' . $invoice->vessel . '</td>
                <td class="text-val" style="width:25%; border-left:1px solid #00306A;">' . $invoice->pre_carriage_by . '</td>
                <td class="text-val" style="border-left:1px solid #00306A; width:25%;">' . $invoice->port_of_receipt . '</td>
            </tr>
            <tr style="">
                <td class="shipper_heading" style="width:25%;border-top:1px solid #00306A;">Port of Loading</td>
                <td class="shipper_heading" style="width:25%;border-left:1px solid #00306A;border-top:1px solid #00306A;">Port of Discharge</td>
                <td class="shipper_heading" style="width:25%;border-top:1px solid #00306A;border-left:1px solid #00306A;">Place of Delivery / Final Destination</td>
                <td class="shipper_heading" style="border-left:1px solid #00306A;width:25%;border-top:1px solid #00306A;"> Country of Origin</td>
            </tr>
            <tr style="">
                <td class="text-val" style="width:25%;">' . $invoice->port_of_loading . '</td>
                <td class="text-val" style="border-left:1px solid #00306A; width:25%;">' . $invoice->port_of_dischange . '</td>
                <td class="text-val" style="width:25%;border-left:1px solid #00306A;">' . $invoice->delivery_place . '</td>
                <td class="text-val" style="border-left:1px solid #00306A; width:25%;">' . $invoice->contry_origin . '</td>
            </tr>
  

        </table>
        <div class="header_s py-2" style="height: auto;" style="background: #00306A;">
            <div class="row px-4">
                <div class="col-md-12 text-center">
                    <div class="bill_of_heading" style="color:white;text-align:center;font-weight:bold;padding:6px 0; style="background: #00306A;" >
                        PARTICULARS FURNISHED BY THE SHIPPER - NOT CECKED BY CARRIER - CARRIER NOT RESPONSIBLE
                    </div>
                </div>
            </div>
        </div>
        <div class="row ">
            <table class="table table_part itemTable" style="margin-bottom:0px;table-layout: fixed;">
                <thead>
                    <tr style="font-size:12px;">
                        <th class="table_part_heading" style="width: 22%; border-bottom:1px solid #00306A;border-left:0px !important">Container No. / Type</th>
                        <th class="table_part_heading" style="width: 15%; border-bottom:1px solid #00306A;">Seal No/Number of <br>Packages </th>
                        <th class="table_part_heading" style="width: 33%; border-bottom:1px solid #00306A;">Numbers/Kind of packages/description of goods</th>
                        <th class="table_part_heading" style="width: 15%; border-bottom:1px solid #00306A;">Gross Weight/Net Weight</th>
                        <th class="table_part_heading" style="width: 15%; border-bottom:1px solid #00306A;">Measurement</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="" style="text-align:center;">BOOKING VOLUM B Q CONTAINER STC.</td>
                        <td></td>
                        <td style="border-right:0 !important;"></td>
                    </tr>';
                    $count = count($users->item);
                    $total_lot = 0;
                    $total_Gross_web = 0;
                    $total_net_weight = 0;
                    $total_Measurment = 0;

                    if(count($users->item) > $item_row){
                        $i = 1;
                            
                            foreach($users->item as $user){
                                if($i <= $item_row){
                                if($user->container_no != ""){

                                    $total_lot = $total_lot + $user->container_package;
                                    $total_Gross_web = $total_Gross_web + $user->Gross_web;
                                    $total_net_weight = $total_net_weight + $user->net_weight;
                                    $total_Measurment = $total_Measurment + $user->Measurment;

                                    $counttotal = strlen($user->container_no.$user->container_type);
                                  
                                   if($counttotal < strlen($user->seal_no.$user->container_package)){
                                    $counttotal = strlen($user->seal_no.$user->container_package);
                                   } 
                                   if($counttotal < strlen($user->description_goods)){
                                    $counttotal = strlen($user->description_goods);
                                    $description_good_address = substr_count($user->description_goods,"</p>");
                                    $description_good_array = explode('</p>',$user->description_goods);
                                    $count = $count + $description_good_address;
                                   } 
                                   if($counttotal < strlen($user->Gross_web.$user->net_weight)){
                                    $counttotal = strlen($user->Gross_web.$user->net_weight);
                                   } 
                                   if($counttotal < strlen($user->Measurment)){
                                    $counttotal = strlen($user->Measurment);
                                   }
                                   
                                   $counttotal1 = round($counttotal / 45);
                                 
                                   if($counttotal1 > 1){
                                    $count = $count + $counttotal1;
                                   }
                                    
                                $HTMLContent .='<tr>
                                <td style="word-break: break-all;"><div style="width: inherit;">'.$user->container_no.'|'.$user->container_type.'</div></td>
                                    <td style="word-break: break-all;">'.$user->seal_no.'|'.$user->container_package.' LOT</td>
                                    
                                    <td style="word-break: break-all;" >'.$user->description_goods.'</td>
                                    <td style="word-break: break-all;">'.$user->Gross_web.' Kgs|'.$user->net_weight.' Kgs</td>
                                    <td style="word-break: break-all; border-right:0 !important;">'.$user->Measurment.'</td>
                                </tr>';

                                }
                                $i++;
                            }
                           
                        }
                    }else{
                        foreach($users->item as $user){
                            if($user->container_no != ""){

                                $total_lot = $total_lot + (int)$user->container_package;
                                $total_Gross_web = $total_Gross_web + (int)$user->Gross_web;
                                $total_net_weight = $total_net_weight + (int)$user->net_weight;
                                $total_Measurment = $total_Measurment + (int)$user->Measurment;

                                $counttotal = strlen($user->container_no.$user->container_type);
                                  
                                if($counttotal < strlen($user->seal_no.$user->container_package)){
                                   $counttotal = strlen($user->seal_no.$user->container_package);
                                } 
                                if($counttotal < strlen($user->description_goods)){
                                   $counttotal = strlen($user->description_goods);
                                } 
                                if($counttotal < strlen($user->Gross_web.$user->net_weight)){
                                   $counttotal = strlen($user->Gross_web.$user->net_weight);
                                } 
                                if($counttotal < strlen($user->Measurment)){
                                $counttotal = strlen($user->Measurment);
                                }
                                
                                $counttotal1 = round($counttotal / 45);
                                
                                if($counttotal1 > 1){
                                $count = $count + $counttotal1 - 1;
                                }
                              $HTMLContent .='<tr>
                               <td style="word-break: break-all;"><div style="width: inherit;">'.$user->container_no.'| '.$user->container_type.'</div></td>
                                  <td style="word-break: break-all;">'.$user->seal_no.'| '.$user->container_package.' LOT</td>
                                  
                                  <td style="word-break: break-all;">'.$user->description_goods.'</td>
                                  <td style="word-break: break-all;">'.$user->Gross_web.' Kgs| '.$user->net_weight.' Kgs</td>
                                  <td style="word-break: break-all;border-right:0 !important;">'.$user->Measurment.'</td>
                              </tr>';
                            }
                        }
                    }

                    $HTMLContent .='<tr>
                               <td style="word-break: break-all;"><div style="width: inherit;"></div></td>
                                  <td style="word-break: break-all;"></td>
                                  
                                  <td style="word-break: break-all;">'.$total_lot.' LOT</td>
                                  <td style="word-break: break-all;"></td>
                                  <td style="word-break: break-all;border-right:0 !important;"></td>
                              </tr>';
                    $HTMLContent .='<tr>
                              <td style="word-break: break-all;"><div style="width: inherit;"></div></td>
                                 <td style="word-break: break-all;"></td>
                                 
                                 <td style="word-break: break-all;">'.$total_Gross_web.' Kgs</td>
                                 <td style="word-break: break-all;"></td>
                                 <td style="word-break: break-all;border-right:0 !important;"></td>
                             </tr>';
                    $HTMLContent .='<tr>
                             <td style="word-break: break-all;"><div style="width: inherit;"></div></td>
                                <td style="word-break: break-all;"></td>
                                
                                <td style="word-break: break-all;">'.$total_net_weight.' Kgs</td>
                                <td style="word-break: break-all;"></td>
                                <td style="word-break: break-all;border-right:0 !important;"></td>
                            </tr>';
                    $HTMLContent .='<tr>
                            <td style="word-break: break-all;"><div style="width: inherit;"></div></td>
                               <td style="word-break: break-all;"></td>
                               
                               <td style="word-break: break-all;">'.$total_Measurment.'</td>
                               <td style="word-break: break-all;"></td>
                               <td style="word-break: break-all;border-right:0 !important;"></td>
                           </tr>';                           
                                      
                   
                    //dump('count '.$count);
                    //dump('shipper_count_address '.$shipper_count_address);
                    
                    $shipper_count = 0;
                    if($shipper_count_address > $shipper_row){
                        $shipper_count = $shipper_count_address - $shipper_row;
                    }
                    
                  $blankrow = $item_row - $count;
                 // dd('blankrow '.$blankrow);
                  
                    if($count < $item_row && $shipper_count < $blankrow && $shipper_count_address > $shipper_row){
                    $showShipperAddr = 1; 
                    $blankrow--;
                    $HTMLContent .='<tr>
                    
                        <td style="word-break: break-all;">
                            <div style="width: inherit;"></div>
                        </td>
                        <td style="word-break: break-all;"></td>
                        <td style="">';
                       // if($shipper_count_address > 3){
                        $HTMLContent .= ' * ';
                            for($x = $shipper_row; $x < $shipper_count_address; $x++){
                                $count++;
                                $blankrow--;
                                $HTMLContent .= ' '.   $shipper_address_array[$x] .'';
                            }
                       // }  
                        $HTMLContent .='</td>
                        <td style=""></td>
                        <td style="border-right:0 !important;"></td>
                    </tr>';
                    
                  }
                  
                  $consignee_count = 0;
                  if($consignee_count_address > $consignee_row){
                    $consignee_count = $consignee_count_address - $consignee_row;
                  }
                  
                  if($count < $item_row && $consignee_count < $blankrow && $consignee_count_address > $consignee_row){
                    $showConsigneeAddr = 1;
                    $blankrow--;
                    $HTMLContent .='<tr>
                    
                        <td style="word-break: break-all;">
                            <div style="width: inherit;"></div>
                        </td>
                        <td style="word-break: break-all;"></td>
                        <td style="">';
                       // if($consignee_count_address > 3){
                        $HTMLContent .= ' ** ';
                            for($x = $consignee_row; $x < $consignee_count_address; $x++){
                                $count++;
                                $blankrow--;
                                $HTMLContent .= ' '.   $consignee_address_array[$x] .'';
                            }
                       // }  
                        $HTMLContent .='</td>
                        <td style=""></td>
                        <td style="border-right:0 !important;"></td>
                    </tr>';
                  }

                  $notify_count = 0;
                  if($notify_count_address > $notify_row){
                    $notify_count = $notify_count_address - $notify_row;
                  }
                  
                  
                  if($count < $item_row && $notify_count < $blankrow && $notify_count_address > $notify_row){
                    $showNotifyAddr = 1;
                    $blankrow--;
                    $HTMLContent .='<tr>
                    
                        <td style="word-break: break-all;">
                            <div style="width: inherit;"></div>
                        </td>
                        <td style="word-break: break-all;"></td>
                        <td style="">';
                        //if($notify_count_address > 3){
                            $HTMLContent .= ' *** ';
                            for($x = $notify_row; $x < $notify_count_address; $x++){
                                $count++;
                                $blankrow--;
                                $HTMLContent .= ' '.   $notify_address_array[$x] .'';
                            }
                       // }  
                        $HTMLContent .='</td>
                        <td style=""></td>
                        <td style="border-right:0 !important;"></td>
                    </tr>';
                  }

                  $agent_count = 0;
                  if($agent_count_address > $agent_row){
                    $agent_count = $agent_count_address - $agent_row;
                  }

                  if($count < $item_row && $agent_count < $blankrow && $agent_count_address > $agent_row){
                    $showAgentAddr = 1;
                    $blankrow--;
                    $HTMLContent .='<tr>
                    
                        <td style="word-break: break-all;">
                            <div style="width: inherit;"></div>
                        </td>
                        <td style="word-break: break-all;"></td>
                        <td style="">';
                        //if($notify_count_address > 3){
                            $HTMLContent .= ' **** ';
                            for($x = $agent_row; $x < $agent_count_address; $x++){
                                $count++;
                                $blankrow--;
                                $HTMLContent .= ' '.   $agent_address_array[$x] .'';
                            }
                       // }  
                        $HTMLContent .='</td>
                        <td style=""></td>
                        <td style="border-right:0 !important;"></td>
                    </tr>';
                  }

                  for($user=$count;$user < $item_row;$user++){
                    $HTMLContent .='<tr>
                    <td><div style="height:12px;"></div></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="border-right:0 !important;"></td>
                    </tr>';

                  }
                //   $HTMLContent .='<tr>
                    
                //         <td style="word-break: break-all;">
                //             <div style="width: inherit;">wwwwwwwwwww wwwwwwwwwwwwwwwwwwwww wwwwwwwwwwwww</div>
                //         </td>
                //         <td style="word-break: break-all;">b89123456789sdfgb89123456789sdfg</td>


                //         <td style="">1234567890</td>
                //         <td style="">1234567890</td>
                //         <td style="border-right:0 !important;">123456</td>
                //     </tr>';

                    $HTMLContent .= '<tr style="border-bottom:1px solid #00306A;">
                        <td></td>
                        <td></td>
                        <td class="" style="text-align:center; color:#00306A;">SHIPPER LOAD COUNT AND SEAL</td>
                        <td></td>
                        <td style="border-right:0 !important;"></td>
                    </tr>
                </tbody>
            </table>
            <!-- <div style="width: 100%;">
                      <div style="width: 22%; float: left; display: inline-block; word-wrap: break-word;">TestTestTestTestTest TestTestTestTest</div>
                      <div style="width: 15%; float: left;">1011111111111111</div>
                      <div style="width: 33%; float: left;">222222222222222</div>
                      <div style="width: 15%; float: left;">33333333333333333333333333</div>
                      <div style="width: 14%; float: left; word-wrap: break-word;">44444444444444444444444444444444444</div>
                    </div> -->

            <div class="row table_freight_heading  px-0">
                <div class="col-md-6 border-right-black gradient_border_1 p-0" style="width:42%;border-bottom:5px solid #40475f;">
                    <div class="row align-items-center">
                        <div class="col-md-6 " style="width:34%;float:left;">
                            Freight & Charges:
                        </div>
                        <div class="col-md-6 border-left-black p-0" style="width:64%;">
                            <div style="padding-left:10px;padding-right:10px;" class="bill-comman-class">
                                Cargo shall not delivered unless freight and charges not paid
                            </div>
                        </div>
                    </div>
                    <div class="row border-top-black" style="border-top:1px solid #00306A;">
                        <div class="col-md-4 shipper_heading_collect" style="text-align: left;">
                            Freight & Charges:
                        </div>
                        <!-- <div class="col-md-4 shipper_heading_collect" style="text-align: left;">
                            prepaid
                        </div>
                        <div class="col-md-4 shipper_heading_collect" style="text-align: left;">
                            Collect
                        </div> -->
                    </div>
                    <div class="row align-items-center px-0">
                        <div class="" style="color:black; margin-left:150px">'.$invoice->freight_charges.'</div>
                        <div class="col-md-2" style="width:20%;float:left;">
                            <div class="total_heading">Total:</div>
                        </div>
                        <div class="col-md-10" style="width:80%;border-top:1px solid #00306A;">
                            <div class=""> </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 row">
                        <div class="freight_payable_at_heading col-md-12 px-0">Freight Payable at : <span style="color:black;padding:2px 0;">' . $invoice->freight_payable_at . '</span></div>
                        <!-- <div class="col-md-6 px-0" style="color:black;padding:2px 0;">' . $invoice->freight_payable_at . '</div> -->
                        <div class="row px-0">
                            <div class=" col-md-6 px-0 shipper_heading" style="width:50%;">
                                <div class="shipper_heading px-0">Mode of Shipment:</div>
                                <div style="color:black;padding:2px 0;">FCL/FCL</div>
                            </div>

                            <div class="col-md-6 px-0" style="width:50%;padding:2px 0;">
                                <div class="shipper_headings" style="padding:2px 0;">Shipped on board : <span style="color:black;padding:2px 0;">' . $invoice->shipped_on_board . '</span></div>
                                <!-- <div style="color:black;padding:2px 0;">' . $invoice->shipped_on_board . '</div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 gradient_border_2 shipper_place_text" style="width:57.5%;border-bottom:5px solid #00306A;">
                    <div style="font-size:11px;padding-left:10px;padding-top:5px;">
                        Received by the carrier the Goods as specified above in apparent good order and condition unless otherwise stated, to be transported to such place as agreed, authorised or permitted herein and subject to all the terms and conditions appearing on the front
                        and reverse of this bill of lading to which the merchant agrees by accepting the bill of lading, any local privileges and customs notwithstanding, The particulars given above stated by the shipper and the weight, measure, quantity,
                        condition, contents and value of the goods are unknown to the carrier. In WITNESS whereof one (1) original bill of lading has been signed if not otherwise stated above, the same being accomplished the other(s) if any, to be void,
                        if required by the carrier one (1) original bill of lading must be surrendered duly endorsed in exchange for the Goods or delivery order.
                    </div>

                    <div class="row" style="font-size:11px;padding-left:10px;padding-top:5px;">
                        <div class="col-md-12 px-0">
                            <div class="place_date_text mt-3">
                                <div style="font-weight:bold;"> Place and date of issue:</div>
                            </div>
                            <div style="color:black;">' . $invoice->place_of_issue . ' ' . $invoice->place_of_date . '</div>
                        </div>
                        <div class="row px-0">
                            <div class="col-md-6 px-0">
                                <div class="" style="text-align:left;margin-top:15px;">
                                    <div>Signed as Agents of the Carrier / Agents</div>
                                </div>
                            </div>
                            <div class="col-md-6 float-end px-0 mt-4">
                                <div class=" mt-3 col-md-12" style="text-align:right;margin-top:15px;">
                                    Authorised Signatory
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- <div class="place_date_text mt-3">
                            <div style="font-weight:bold;"> Place and date of issue:</div>
                        </div>
                        <div style="color:black;">dgfhfghdfghdfghdfghdfgh</div>
                        <div class="mt-5" style="text-align:left;margin-top:15px;">
                            <div>Signed as Agents of the Carrier / Agents</div>
                        </div>
                        <div class="float-end mt-3 col-md-12" style="text-align:right;margin-top:15px;">
                            Authorised Signatory
                        </div> -->
                </div>
            </div>';
            if($showShipperAddr == 0 || $showConsigneeAddr == 0 || $showNotifyAddr == 0 || $count > $item_row){
                
            $HTMLContent .='
            <table style="border-top:1px solid #00306A;width: 100%;">
           
            <div class="pagebreak" style="margin-top:50px;">';
            if($count > $item_row){
                $i = 1;
                $HTMLContent .='<div >';
                    foreach($users->item as $user){
                        if($i >= 26){
                        if($user->container_no != ""){
                        $HTMLContent .='<tr>
                        <td style="word-break: break-all;">'.$user->container_no.','.$user->container_type.'</td>
                            <td style="word-break: break-all;">'.$user->seal_no.','.$user->container_package.'</td>
                            
                            <td>'.$user->description_goods.'</td>
                            <td style="">'.$user->Gross_web.','.$user->net_weight.'</td>
                            <td style="border-right:0 !important;">'.$user->Measurment.'</td>
                        </tr>';
                         }
                        }
                   
                    $i++;
                }
                $HTMLContent .='</div>';
            }

            

            if($showShipperAddr == 0){
                $HTMLContent .='<div >';
                $HTMLContent .='<tr>
                    <td style="word-break: break-all;">
                        <div style="width: inherit;"></div>
                    </td>
                    <td style="word-break: break-all;"></td>
                    <td style="">';
                    if($shipper_count_address > $shipper_row){
                        $HTMLContent .= ' * ';
                        for($x = $shipper_row; $x < $shipper_count_address; $x++){
                            $count++;
                            $HTMLContent .= ' '.   $shipper_address_array_new[$x] .'';
                        }
                    }  
                    $HTMLContent .='</td>
                    <td style=""></td>
                    <td style="border-right:0 !important;"></td>
                </tr></div>';
            }

            if($showConsigneeAddr == 0){
                $HTMLContent .='<tr>
                    <td style="word-break: break-all;">
                        <div style="width: inherit;"></div>
                    </td>
                    <td style="word-break: break-all;"></td>
                    <td style="">';
                    if($consignee_count_address > $consignee_row){
                        $HTMLContent .= ' ** ';
                        for($x = $consignee_row; $x < $consignee_count_address; $x++){
                            $count++;
                            $HTMLContent .= ' '.   $consignee_address_array[$x] .'';
                        }
                    }  
                    $HTMLContent .='</td>
                    <td style=""></td>
                    <td style="border-right:0 !important;"></td>
                </tr>';
            }

            if($showNotifyAddr == 0){
                $HTMLContent .='<tr>
                    <td style="word-break: break-all;">
                        <div style="width: inherit;"></div>
                    </td>
                    <td style="word-break: break-all;"></td>
                    <td style="">';
                    if($notify_count_address > $notify_row){
                        $HTMLContent .= ' *** ';
                        for($x = $notify_row; $x < $notify_count_address; $x++){
                            $count++;
                            $HTMLContent .= ' '.   $notify_address_array[$x] .'';
                        }
                    }  
                    $HTMLContent .='</td>
                    <td style=""></td>
                    <td style="border-right:0 !important;"></td>
                </tr>';
            }

            if($showAgentAddr == 0){
                $HTMLContent .='<tr>
                    <td style="word-break: break-all;">
                        <div style="width: inherit;"></div>
                    </td>
                    <td style="word-break: break-all;"></td>
                    <td style="">';
                    if($agent_count_address > $agent_row){
                        $HTMLContent .= ' *** ';
                        for($x = $agent_row; $x < $agent_count_address; $x++){
                            $count++;
                            $HTMLContent .= ' '.   $agent_address_array[$x] .'';
                        }
                    }  
                    $HTMLContent .='</td>
                    <td style=""></td>
                    <td style="border-right:0 !important;"></td>
                </tr>';
            }

            $HTMLContent .='</div>';
        }
            $HTMLContent .='</div>
        
        </body>
        
        
        </html>';


      return $HTMLContent;

        // $filename = "Invoice_abc.pdf";
        // $mpdf = new Mpdf(["autoScriptToLang" => true, "autoLangToFont" => true, 'mode' => 'utf-8', 'format' => 'A4', 'margin_left' => 5, 'margin_right' => 5, 'margin_top' => 5, 'margin_bottom' => 5, 'margin_header' => 0, 'margin_footer' => 0]);
        // $mpdf->WriteHTML($HTMLContent);
        // $mpdf->Output($filename, "I");
    }
}
