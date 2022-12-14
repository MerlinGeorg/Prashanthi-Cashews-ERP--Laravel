<?php

namespace App\Http\Controllers\Stockyard;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Factory;
use App\Models\ShipperDetails;
use App\Models\Stockyard;
use App\Models\StockyardRcnStock;
use App\Models\StockyardRcnStockCombine;
use App\Models\StockyardRcnStockMix;
use App\Models\StockyardRcnStockSplit;
use App\Models\SubAccount;
use Carbon\Carbon;
use Config;
use DataTables;
use Illuminate\Http\Request;

class StockyardRcnStockController extends Controller
{
    /**
     * Stockyard RCN Stock(List).
     *
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Stockyard"], ['name' => "RCN Stock"],
        ];

        return view('stockyard.rcn-stock.list_rcn_stock', [
            'breadcrumbs' => $breadcrumbs]);
    }


    
    public function listStockByFactoryMark(Request $request, $factoryslug,$mark)
    { 
        $data = StockyardRcnStock::select('stockyard_rcn_stocks.slug as slug', 'stockyard_rcn_stocks.lot_number')
            ->where('stockyard_slug', '=', $factoryslug)
            ->where('rcn_mark', '=', $mark)
            ->get();

        return response()->json([
            "success" => true,
            "data" => $data]);
    }
    
    public function StockyardListMixCompine(Request $request, $factoryslug, $stockyard_rcn_stock_slug)
    {
        $data = StockyardRcnStock::where('stockyard_slug', $factoryslug)
            ->select('stockyard_slug', 'lot_number')
            ->where(function($q) {
                $q->where('type','=','split')
                  ->orWhere('type', NULL);
            })
      

            ->get();
            
        return response()->json([
            "success" => true,
            'data' => $data,
        ]);
    }
    /**
     * Stockyard RCN Stock List.
     *
     * @return \Illuminate\Http\Response
     */

    public function listStockyardRcn(Request $request)
    {
        if ($request->ajax()) {
            $stockyardRcnData = StockyardRcnStock::with(['stockyardDetails', 'subAccount'])
                ->filterbyOffice()
                ->latest();

            return Datatables::eloquent($stockyardRcnData)
        
                ->addColumn('stockyard_name', function (StockyardRcnStock $stock) {
                
                    return $stock->stockyardDetails->stockyard_name ?? '';
                })

                ->addColumn('account_name', function (StockyardRcnStock $stock) {
                    return $stock->subAccount->account->account_name . " - " . $stock->subAccount->account_state;
                })
                ->editColumn('rcn_mark', function ($stock) {
                    return Config::get('constants.rcn_marks')[$stock->rcn_mark];
                })
                ->addColumn('action', function ($row) { 
                    return [
                        'view' => \Helper::userAccess('stockyard-rcn-view'),
                        'edit' => \Helper::userAccess('stockyard-rcn-edit'),
                        'delete' => \Helper::userAccess('stockyard-rcn-delete'),
                    ];
                })
                ->editColumn('lot_number', function (StockyardRcnStock $stock) {
                    return $stock->lot_number . \Helper::rcnJobWorkWithBadge($stock->subAccount->slug,
                        $stock->stockyardDetails->sub_account_slug ?? '');
                })
                ->editColumn('stockyard_name', function (StockyardRcnStock $stock) {
                    if ($stock->type == "split") {
                        return $stock->stockyardDetails->stockyard_name . \Helper::rcnStockSplitBadge();
                    } else if ($stock->type == "mix") {
                        return $stock->stockyardDetails->stockyard_name . \Helper::rcnStockMixBadge();
                    } else if ($stock->type == "combine") {
                        return $stock->stockyardDetails->stockyard_name . \Helper::rcnStockCombineBadge();
                    } else {
                        return $stock->stockyardDetails->stockyard_name;
                    }

                })

                ->rawColumns(['lot_number', 'action', 'stockyard_name'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock", 'name' => "Stockyard"], ['name' => "Add RCN Stock"],
        ];

        $stockyards = Stockyard::filterbyOffice()->get();
        $accounts = Account::get();
        $shipper_details = ShipperDetails::get();
        $rcn_marks = Config::get('constants.rcn_marks');

        return view('stockyard.rcn-stock.create_rcn_stock', compact('stockyards', 'accounts', 'shipper_details', 'rcn_marks'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    public function create_splitz()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock/create_split", 'name' => "Stockyard"], ['name' => "Add RCN Stock Split"],
        ];

        $stockyards = Stockyard::get();
        $accounts = Account::get();
        $shipper_details = ShipperDetails::get();
        $rcn_marks = Config::get('constants.rcn_marks');
        $stockyard_rcn_stocks = StockyardRcnStock::select("lot_number", "slug")->orderBy('id', 'DESC')->get();

        return view('stockyard.rcn-stock.create_rcn_stock_split', compact('stockyards', 'accounts', 'shipper_details', 'rcn_marks', 'stockyard_rcn_stocks'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    public function create_mix()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock/create_mix", 'name' => "Stockyard"], ['name' => "Add RCN Stock Mix"],
        ];
        $factories = Factory::select('factory_name', 'slug')->get();
        $stockyards = Stockyard::get();
        $accounts = Account::get();
        $shipper_details = ShipperDetails::get();
        $rcn_marks = Config::get('constants.rcn_marks');
        $stockyard_rcn_stocks = StockyardRcnStock::select("lot_number", "slug")->orderBy('id', 'DESC')->get();

        return view('stockyard.rcn-stock.create_rcn_stock_mix', compact('stockyards', 'factories', 'accounts', 'shipper_details', 'rcn_marks', 'stockyard_rcn_stocks'), [
            'breadcrumbs' => $breadcrumbs]);
    }
    public function create_compine()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock/create_mix", 'name' => "Stockyard"], ['name' => "Add RCN Stock Combine"],
        ];
        $factories = Factory::select('factory_name', 'slug')->get();
        $stockyards = Stockyard::get();
        $accounts = Account::get();
        $shipper_details = ShipperDetails::get();
        $rcn_marks = Config::get('constants.rcn_marks');
        $stockyard_rcn_stocks = StockyardRcnStock::select("lot_number", "slug")->orderBy('id', 'DESC')->get();

        return view('stockyard.rcn-stock.create_rcn_stock_compine', compact('stockyards', 'factories', 'accounts', 'shipper_details', 'rcn_marks', 'stockyard_rcn_stocks'), [
            'breadcrumbs' => $breadcrumbs]);
    }
    public function listStockyardRcnSubAccounts(Request $request)
    {
        $account_slug = $request->acc_slug;
        $sub_accounts = SubAccount::where('account_slug', $account_slug)->get();

        return response()->json([
            'sub_accounts' => $sub_accounts,
        ]);
    }

    public function getLotNumberByStockyard($stockyard_slug): string
    {
        $date = Carbon::now();
        $reset_day = env('FINANCIAL_YEAR_END');
        $curr_date = $date->toDateString();
        $curr_year = Carbon::createFromFormat('Y-m-d', $curr_date)->format('Y');
        $reset_date = $curr_year . "-" . $reset_day;
        $curr_date = Carbon::createFromFormat('Y-m-d', $curr_date);

        $reset_date = Carbon::createFromFormat('Y-m-d', $reset_date);

        $fin_year_chk = $curr_date->eq($reset_date);

        if ($fin_year_chk != true) {

            $lot_obj = StockyardRcnStock::with('stockyardDetails')
                ->where('stockyard_slug', $stockyard_slug)
                ->latest('lot_number')
                ->first();
            if ($lot_obj) {
                $lot_number = $lot_obj->lot_number;
                $stockyard_short_name = $lot_obj->stockyardDetails->stockyard_short_name;
                $lot_number_parts = explode('-', $lot_number);
                $removed_char = substr(end($lot_number_parts), 1);
                $generated_lot_number = $stpad = str_pad((int) $removed_char + 1, 4, "0", STR_PAD_LEFT);
            } else {
                $stockyard = Stockyard::select('stockyard_short_name')
                    ->whereSlug($stockyard_slug)
                    ->first();
                $stockyard_short_name = $stockyard->stockyard_short_name;
                $generated_lot_number = str_pad(1, 4, "0", STR_PAD_LEFT);
            }
        } else {
            $stockyard = Stockyard::select('slug')
                ->whereSlug($stockyard_slug)
                ->first();
            $stockyard_short_name = $stockyard->stockyard_short_name;
            $generated_lot_number = str_pad(1, 4, "0", STR_PAD_LEFT);
        }

        return $stockyard_short_name . '-' . $generated_lot_number;
    }
    public function getLotNumberByAccount($account_slug): string
    {

        $date = Carbon::now();
        $reset_day = env('FINANCIAL_YEAR_END');
        $curr_date = $date->toDateString();
        $curr_year = Carbon::createFromFormat('Y-m-d', $curr_date)->format('Y');
        $reset_date = $curr_year . "-" . $reset_day;
        $curr_date = Carbon::createFromFormat('Y-m-d', $curr_date);
        $reset_date = Carbon::createFromFormat('Y-m-d', $reset_date);
        $fin_year_chk = $curr_date->eq($reset_date);

        if ($fin_year_chk != true) {
            $lot_obj = StockyardRcnStock::with('account')
                ->where('account_slug', $account_slug)
                ->latest('account_lot_number')
                ->first();

            if ($lot_obj && $lot_obj->account_lot_number) {
                $lot_number = $lot_obj->account_lot_number;
                $lot_number_parts = explode('-', $lot_number);
                $removed_char = substr(end($lot_number_parts), 1);
                $generated_lot_number = $stpad = str_pad((int) $removed_char + 1, 4, "0", STR_PAD_LEFT);
                $account_short_name = $lot_obj->account->account_short_name;
            } else {
                $account = Account::select('account_short_name')
                    ->whereSlug($account_slug)
                    ->first();
                $account_short_name = $account->account_short_name;
                $generated_lot_number = str_pad(1, 4, "0", STR_PAD_LEFT);
            }
        } else {
            $account = Account::select('account_short_name')
                ->whereSlug($account_slug)
                ->first();
            $account_short_name = $account->account_short_name;
            $generated_lot_number = str_pad(1, 4, "0", STR_PAD_LEFT);
        }
        return $account_short_name . '-' . $generated_lot_number;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store_splitz(Request $request)
    {
        $stockyard_rcn_stock = StockyardRcnStock::where('slug', $request->stockyard_rcn_stock_slug)
            ->first();

            $validator_messages = [
                'stockyard_slug.required' =>   "Stockyard is Required" ,
                'stockyard_rcn_stock_slug.required' =>   "Lot Number is Required" ,

                'sub_account_id.required' =>  "Sub Account is required"  ,
                'account_id.required' =>  "Account is required"  ,

            ];

        $validator_conditions = $this->getValidationConditionSplit($request, $stockyard_rcn_stock);

        $validator = \Validator::make(request()->all(), $validator_conditions,$validator_messages);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        } else {

            $rcn_mark = $stockyard_rcn_stock->rcn_mark;
            $data['lot_number'] = $this->getLotNumberByStockyard($request->stockyard_slug);
            $data['type'] = $request->type;

            $data['account_slug'] = $request->account_id;
            $data['account_lot_number'] = $this->getLotNumberByAccount($request->account_id, $request->sub_account_id);

   

            if ($data['type'] == "split") {


                $stockyardRcnStock = new StockyardRcnStock();
                $stockyardRcnStock->stockyard_slug = $request->stockyard_slug;
                $stockyardRcnStock->lot_number = $data['lot_number'];
                $stockyardRcnStock->account_slug = $data['account_slug'];
                $stockyardRcnStock->sub_account_id = $request->sub_account_id;
                $stockyardRcnStock->shipper_company_slug = "";
                $stockyardRcnStock->rcn_mark = $rcn_mark;
                $stockyardRcnStock->be_number = "";
                $stockyardRcnStock->bl_number = "";
                $stockyardRcnStock->invoice_number = "";
                $stockyardRcnStock->warehouse_slug = "";
                $stockyardRcnStock->account_lot_number = $data['account_lot_number'];
                $stockyardRcnStock->bl_despatched_rcn_bags = $request->rcn_bags;
                $stockyardRcnStock->bl_despatched_rcn_weight = $request->rcn_kg;

                $stockyardRcnStock->balance_rcn_bag = $request->rcn_bags;
                $stockyardRcnStock->balance_rcn_stock = $request->rcn_kg;

                $stockyardRcnStock->type = "split";
                $stockyardRcnStock->save();




                $stock_split = new StockyardRcnStockSplit();
                $stock_split->slug = $stockyardRcnStock->slug;
                $stock_split->lot_number = $data['lot_number'];
                $stock_split->account = $data['account_slug'];
                $stock_split->rcn_kg = $request->rcn_kg;
                $stock_split->rcn_bags = $request->rcn_bags;
                $stock_split->stockyard = $request->stockyard_rcn_stock_slug;
                $stock_split->save();

              

                $parent_rcn_bag = $stockyard_rcn_stock->balance_rcn_bag;
                $parent_rcn_weight = $stockyard_rcn_stock->balance_rcn_stock;

                $stockyard_rcn_stock->balance_rcn_bag = $parent_rcn_bag - $request->rcn_bags;
                $stockyard_rcn_stock->balance_rcn_stock = $parent_rcn_weight - $request->rcn_kg;

                $stockyard_rcn_stock->update();

            }

            return redirect("/stockyard/rcn-stock")
                ->with('success', 'Stockyard Spilt RCN Stock added successfully.');
        }
    }

    public function store_mix(Request $request)
    {
    
        $stockyard_rcn_stock = StockyardRcnStock::where('slug',$request->stockyard_rcn_stock_slug)
                ->first();
                $validator_messages = [
                    'lot_numbers.min'  => 'Please Select Atleast 2 lot Number',
                    'lot_numbers.*.rcn_bags.required' =>   "Rcn Bags is Required" ,
                    'lot_numbers.*.rcn_weight.required' =>  "Rcn Weight is Required"  ,
                    'lot_numbers.*.rcn_bags.max' =>   "Rcn Bags is limit is exceeded" ,
                    'lot_numbers.*.rcn_weight.max' =>  "Rcn Weight limit Is exceeded"  ,
                    'lot_numbers.*.stockyard_rcn_stock_slug.required' =>  "Lot number is required"  ,
                    'stockyard_slug.required' =>  "Stoackyard is required"  ,
                    'sub_account_id.required' =>  "Sub Account is required"  ,
                    'account_id.required' =>  "Account is required"  ,
                    

                ];
        $validator_conditions = $this->getValidationConditionMix($request, $stockyard_rcn_stock);

        $validator = \Validator::make(request()->all(), $validator_conditions,  $validator_messages);


        if ($validator->fails()) {
           
            return \Redirect::back()->withErrors($validator)->withInput();
        } else {

            if(sizeof($request->lot_numbers)>1){
                foreach ($request->lot_numbers as $key => $rcn_bags) {
        
                    $lot_numbers[] = $rcn_bags['stockyard_rcn_stock_slug'];
        
                }
                $stockyard_rcn_stock = StockyardRcnStock::whereIn('slug',$lot_numbers)
                    ->get();

            

                    $validator_conditions = $this->getValidationConditionMix($request, $stockyard_rcn_stock->toArray());

                    $validator = \Validator::make(request()->all(), $validator_conditions,  $validator_messages);
                    if ($validator->fails()) {
                   
                        return \Redirect::back()->withErrors($validator)->withInput();
                    } 
                  
            }

       

            $total_bags = 0;
            $total_weight = 0;
            $lot_numbers = array();

            foreach ($request->lot_numbers as $key => $rcn_bags) {

                $total_bags = $total_bags + ($rcn_bags['rcn_bags']);
                $total_weight = $total_weight + ($rcn_bags['rcn_weight']);

                $lot_numbers[] = $rcn_bags['stockyard_rcn_stock_slug'];

            }

       

            foreach ($request->lot_numbers as $key => $lot) {
                $stocks = StockyardRcnStock::where('lot_number', $lot['stockyard_rcn_stock_slug'])->first();
                if(isset( $stocks )){
          
                $stocks->balance_rcn_bag = $stocks->balance_rcn_bag - $lot['rcn_bags'];
           
                $stocks->balance_rcn_stock = $stocks->balance_rcn_stock - $lot['rcn_weight'];
                $stocks->save();
                }
            }

            $rcn_mark = "un_defined";
            $data['lot_number'] = $this->getLotNumberByStockyard($request->stockyard_slug);
            $data['type'] = $request->type;

            $data['account_slug'] = $request->account_id;
            $data['account_lot_number'] = $this->getLotNumberByAccount($request->account_id, $request->sub_account_id);

      

            if ($data['type'] == "mix") {

                $stock_split = new StockyardRcnStockMix();
                $stock_split->lot_number = $data['lot_number'];
                $stock_split->account = $data['account_slug'];
   
                $stock_split->stockyard = json_encode($request->lot_numbers);
                $stock_split->save();

                $stockyardRcnStock = new StockyardRcnStock();
                $stockyardRcnStock->stockyard_slug = $request->stockyard_slug;
                $stockyardRcnStock->lot_number = $data['lot_number'];
                $stockyardRcnStock->account_slug = $data['account_slug'];
                $stockyardRcnStock->sub_account_id = $request->sub_account_id;
                $stockyardRcnStock->shipper_company_slug = "";
                $stockyardRcnStock->rcn_mark = $rcn_mark;
                $stockyardRcnStock->be_number = "";
                $stockyardRcnStock->bl_number = "";
                $stockyardRcnStock->invoice_number = "";
                $stockyardRcnStock->warehouse_slug = "";
                $stockyardRcnStock->account_lot_number = $data['account_lot_number'];
                $stockyardRcnStock->bl_despatched_rcn_bags = $total_bags;
                $stockyardRcnStock->bl_despatched_rcn_weight = $total_weight;

                $stockyardRcnStock->balance_rcn_bag = $total_bags;
                $stockyardRcnStock->balance_rcn_stock = $total_weight;

                $stockyardRcnStock->out_turn = $request->out_turn??0;
                $stockyardRcnStock->nut_count = $request->nut_count??0;
                $stockyardRcnStock->rejection = $request->rejection??0;

                $stockyardRcnStock->type = "mix";
                $stockyardRcnStock->save();

            }

            return redirect("/stockyard/rcn-stock")
                ->with('success', 'Stockyard Mix RCN Stock added successfully.');
        }
    }

    public function store_compine(Request $request)
    {

   
        $stockyard_rcn_stock = StockyardRcnStock::where('slug',$request->stockyard_rcn_stock_slug)
                ->first();
            $validator_messages = [
                'lot_numbers.min'  => 'Please Select Atleast 2 lot Number',
                'lot_numbers.*.rcn_bags.required' =>   "Rcn Bags is Required" ,
                'lot_numbers.*.rcn_weight.required' =>  "Rcn Weight is Required"  ,
                'lot_numbers.*.rcn_bags.max' =>   "Rcn Bags is limit is exceeded" ,
                'lot_numbers.*.rcn_weight.max' =>  "Rcn Weight limit Is exceeded"  ,
                'lot_numbers.*.stockyard_rcn_stock_slug.required' =>  "Lot number is required"  ,
                'stockyard_slug.required' =>  "Stoackyard is required"  ,
                'sub_account_id.required' =>  "Sub Account is required"  ,
                'account_id.required' =>  "Account is required"  ,
                'rcn_mark.required' =>  "Rcn Mark is required"  ,
                
            ];

        $validator_conditions = $this->getValidationConditionCompine($request, $stockyard_rcn_stock);

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages );

         if ($validator->fails()) {
             return \Redirect::back()->withErrors($validator)->withInput();
         } else {

      

            if(sizeof($request->lot_numbers)>1){
                foreach ($request->lot_numbers as $key => $rcn_bags) {

                    $lot_numbers[] = $rcn_bags['stockyard_rcn_stock_slug'];    
                    
        
                }
                $stockyard_rcn_stock = StockyardRcnStock::whereIn('slug',$lot_numbers)
                    ->get();

              

                    $validator_conditions = $this->getValidationConditionCompine($request,$stockyard_rcn_stock->toArray());

                    $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages );
            
                     if ($validator->fails()) {
                         return \Redirect::back()->withErrors($validator)->withInput();
                     }


            }


        $total_bags = 0;
        $total_weight = 0;
        $lot_numbers = array();

        foreach ($request->lot_numbers as $key => $rcn_bags) {

            $total_bags = $total_bags + ($rcn_bags['rcn_bags']);
            $total_weight = $total_weight + ($rcn_bags['rcn_weight']);

            $lot_numbers[] = $rcn_bags['stockyard_rcn_stock_slug'];

        }


        foreach ($request->lot_numbers as $key => $lot) {
            $stocks = StockyardRcnStock::where('lot_number', $lot['stockyard_rcn_stock_slug'])->first();
            if(isset( $stocks )){
                $stocks->balance_rcn_bag = $stocks->balance_rcn_bag - $lot['rcn_bags'];
            
                $stocks->balance_rcn_stock = $stocks->balance_rcn_stock - $lot['rcn_weight'];
                $stocks->save();

            }
       
  
          
        }

        $rcn_mark = $request->rcn_mark;
        $data['lot_number'] = $this->getLotNumberByStockyard($request->stockyard_slug);
        $data['type'] = $request->type;

        $data['account_slug'] = $request->account_id;
        $data['account_lot_number'] = $this->getLotNumberByAccount($request->account_id, $request->sub_account_id);



        if ($data['type'] == "combine") {

            $stock_split = new StockyardRcnStockCombine();
            $stock_split->lot_number = $data['lot_number'];
            $stock_split->account = $data['account_slug'];
    
            $stock_split->stockyard = json_encode($request->lot_numbers);
            $stock_split->save();

            $stockyardRcnStock = new StockyardRcnStock();
            $stockyardRcnStock->stockyard_slug = $request->stockyard_slug;
            $stockyardRcnStock->lot_number = $data['lot_number'];
            $stockyardRcnStock->account_slug = $data['account_slug'];
            $stockyardRcnStock->sub_account_id = $request->sub_account_id;
            $stockyardRcnStock->shipper_company_slug = "";
            $stockyardRcnStock->rcn_mark = $rcn_mark;
            $stockyardRcnStock->be_number = "";
            $stockyardRcnStock->bl_number = "";
            $stockyardRcnStock->invoice_number = "";
            $stockyardRcnStock->warehouse_slug = "";
            $stockyardRcnStock->account_lot_number = $data['account_lot_number'];
            $stockyardRcnStock->bl_despatched_rcn_bags = $total_bags;
            $stockyardRcnStock->bl_despatched_rcn_weight = $total_weight;

            $stockyardRcnStock->balance_rcn_bag = $total_bags;
            $stockyardRcnStock->balance_rcn_stock = $total_weight;

            $stockyardRcnStock->out_turn = $request->out_turn??0;
            $stockyardRcnStock->nut_count = $request->nut_count??0;
            $stockyardRcnStock->rejection = $request->rejection??0;

            $stockyardRcnStock->type = "combine";
            $stockyardRcnStock->save();

        }

        return redirect("/stockyard/rcn-stock")
            ->with('success', 'Stockyard Compine RCN Stock added successfully.');
          }
    }

    public function getValidationConditionCompine($request, $stockyard_rcn_stocks): array
    {
        $validator_conditions = [

            'rcn_mark' => 'required',
            'stockyard_slug' => 'required',
            'account_id' => 'required',
            'sub_account_id' => 'required',
            'lot_numbers' => 'required|array|min:2',
            'lot_numbers.*.stockyard_rcn_stock_slug' => 'required',
            'out_turn' => 'required',
        ];


        $sizering_slugs = request()->lot_numbers;


   

  
          $index =0;
        foreach ( $sizering_slugs as $sizering) {
          
        
                $validator_conditions["lot_numbers.{$index}.rcn_bags"] = 'required|numeric|max:99999'  ; 
                $validator_conditions["lot_numbers.{$index}.rcn_weight"] = 'required|numeric|max:99999'  ;
            

            $index ++;
   
             
              }

              if (sizeof($request->lot_numbers) > 1) {
                foreach ($request->lot_numbers as $key => $rcn_bags) {
    
                    $lot_numbers[] = $rcn_bags['stockyard_rcn_stock_slug'];
                }
    
                $stockyard_rcn_stock = StockyardRcnStock::whereIn('lot_number', $lot_numbers)
                ->get();
    
                foreach ($lot_numbers as $k => $lot_number) {
    
                    $item = $stockyard_rcn_stock->where('lot_number', $lot_number)->first();
    
                    $validator_conditions["lot_numbers.{$k}.rcn_bags"] = 'required|numeric|max:' . $item->balance_rcn_bag;
                    $validator_conditions["lot_numbers.{$k}.rcn_weight"] = 'required|numeric|max:' . $item->balance_rcn_stock;
                }
            }
          
         
        return $validator_conditions;

    }
    public function getValidationConditionMix($request, $stockyard_rcn_stocks): array
    {
       
        $validator_conditions = [

            'stockyard_slug' => 'required',
            'account_id' => 'required',
            'sub_account_id' => 'required',
            'lot_numbers' => 'required|array|min:2',
            'out_turn' => 'required',
            'lot_numbers.*.stockyard_rcn_stock_slug' => 'required',
        ];

       

        $sizering_slugs = request()->lot_numbers;
     

   
          $index =0;
        foreach ( $sizering_slugs as $sizering) {
          

                $validator_conditions["lot_numbers.{$index}.rcn_bags"] = 'required|numeric|max:99999'  ; 
                $validator_conditions["lot_numbers.{$index}.rcn_weight"] = 'required|numeric|max:99999'  ;
            
            $index ++;
             
              }


        if (sizeof($request->lot_numbers) > 1) {
            foreach ($request->lot_numbers as $key => $rcn_bags) {

                $lot_numbers[] = $rcn_bags['stockyard_rcn_stock_slug'];
            }

            $stockyard_rcn_stock = StockyardRcnStock::whereIn('lot_number', $lot_numbers)
            ->get();

            foreach ($lot_numbers as $k => $lot_number) {

                $item = $stockyard_rcn_stock->where('lot_number', $lot_number)->first();

                $validator_conditions["lot_numbers.{$k}.rcn_bags"] = 'required|numeric|max:' . $item->balance_rcn_bag;
                $validator_conditions["lot_numbers.{$k}.rcn_weight"] = 'required|numeric|max:' . $item->balance_rcn_stock;
            }
        }
      
       
        return $validator_conditions;

    }

    public function getValidationConditionSplitUp($request, $stockyard_rcn_stock): array
    {
        $validator_conditions = [

            'rcn_bags' => 'required',
            'rcn_kg' => 'required',
         
        ];

        if ($stockyard_rcn_stock) {

            $validator_conditions['rcn_bags'] = 'required|integer|max:' . $stockyard_rcn_stock->balance_rcn_bag;
            $validator_conditions['rcn_kg'] = 'required|numeric|max:' . $stockyard_rcn_stock->balance_rcn_stock;
        }

        return $validator_conditions;

    }


    public function getValidationConditionSplit($request, $stockyard_rcn_stock): array
    {
        $validator_conditions = [

            'rcn_bags' => 'required',
            'rcn_kg' => 'required',
            'stockyard_rcn_stock_slug' => 'required',
            'stockyard_slug' => 'required',
            'account_id' => 'required',
            'sub_account_id' => 'required',
        ];

        if ($stockyard_rcn_stock) {

            $validator_conditions['rcn_bags'] = 'required|integer|max:' . $stockyard_rcn_stock->balance_rcn_bag;
            $validator_conditions['rcn_kg'] = 'required|numeric|max:' . $stockyard_rcn_stock->balance_rcn_stock;
        }

        return $validator_conditions;

    }

    public function store(Request $request)
    {
        $validator_messages = [
            'stockyard_slug.required' => 'Please select a stockyard',
            'account_id.required' => 'Please select an account',
            'sub_account_id.required' => 'Please select a sub account',
            'rcn_mark.required' => 'Please select a RCN mark',
            'shipper_company_slug.required' => 'Please select a shipper company',
            'be_number.required' => 'Please enter BE number',
            'bl_number.required' => 'Please enter BL number',
            'invoice_number.required' => 'Please enter invoice number',
            'bl_despatched_rcn_weight.required' => 'Please enter dispatched RCN weight',
            'bl_despatched_rcn_bags.required' => 'Please enter dispatched RCN bags',
            'out_turn.required' => 'Please enter out turn',
            'nut_count.required' => 'Please enter nut count',
            'rejection.required' => 'Please enter rejection',
            'warehouse_slug.required' => 'Please select a warehouse',
            'total_containers.required' => 'Please enter no. of containers',
        ];
        $validator_conditions = [
            'stockyard_slug' => 'required',
            'account_id' => 'required',
            'sub_account_id' => 'required',
            'rcn_mark' => 'required',
            'shipper_company_slug' => 'required',
            'be_number' => 'required',
            'bl_number' => 'required',
            'invoice_number' => 'required',
            'bl_despatched_rcn_weight' => 'required',
            'bl_despatched_rcn_bags' => 'required',
            'out_turn' => 'required',
            'nut_count' => 'required',
            'rejection' => 'required',
            'warehouse_slug' => 'required',
            'total_containers' => 'required',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        } else {
            $data['balance_rcn_stock'] = 0.00;
            $data['balance_rcn_bag'] = 0;
            $data['account_slug'] = $request->account_id;
            $data['lot_number'] = $this->getLotNumberByStockyard($request->stockyard_slug);
            $data['account_lot_number'] = $this->getLotNumberByAccount($request->account_id, $request->sub_account_id);
            $stockyardRcnStock = StockyardRcnStock::create(array_merge($request->all(), $data));
            return redirect("/stockyard/rcn-stock/$stockyardRcnStock->slug")
                ->with('success', 'Stockyard RCN Stock added successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $stockyard_rcn_data = StockyardRcnStock::with('stockyardDetails', 'subAccount', 'shipperCompany')
            ->where('slug', $slug)
            ->first();

        $sub_account = SubAccount::with('account')
            ->where('id', $stockyard_rcn_data->sub_account_id)
            ->first();

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock", 'name' => "Stockyard"], ['name' => "RCN Stock Details"],
        ];

        return view('stockyard.rcn-stock.show_rcn_stock', compact('stockyard_rcn_data', 'sub_account'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    public function view_split($slug)
    {
        $stockyard_rcn_data = StockyardRcnStock::with('stockyardDetails', 'subAccount', 'shipperCompany')
            ->where('slug', $slug)
            ->first();

        $stockyard_rcn_split_data = StockyardRcnStockSplit::
            where('slug', $slug)
            ->first();
        $stockyard = $stockyard_rcn_split_data->stockyard;
        $parent = StockyardRcnStock::where('slug', $stockyard)->first();
        $parent_lot_number = $parent->lot_number;

        $sub_account = SubAccount::with('account')
            ->where('id', $stockyard_rcn_data->sub_account_id)
            ->first();

        $accounts = Account::get();
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock", 'name' => "Stockyard"], ['name' => "RCN Stock Details"],
        ];

        return view('stockyard.rcn-stock.show_rcn_stock_split', compact('stockyard_rcn_data', 'accounts', 'sub_account', 'parent_lot_number'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    public function view_compine($slug)
    {
        $stockyard_rcn_data = StockyardRcnStock::with('stockyardDetails', 'subAccount', 'shipperCompany')
            ->where('slug', $slug)
            ->first();

        $lot_number = $stockyard_rcn_data->lot_number;
        $stockyard_rcn_split_data = StockyardRcnStockCombine::
            where('lot_number', $lot_number)
            ->first();

        $stockyard = $stockyard_rcn_split_data->stockyard;
        $lot_numbers = json_decode($stockyard);

        $sub_account = SubAccount::with('account')
            ->where('id', $stockyard_rcn_data->sub_account_id)
            ->first();

        $accounts = Account::get();
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock", 'name' => "Stockyard"], ['name' => "RCN Stock Details"],
        ];

        return view('stockyard.rcn-stock.show_rcn_stock_compine', compact('stockyard_rcn_data', 'accounts', 'sub_account', 'lot_numbers'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    public function view_mix($slug)
    {
        $stockyard_rcn_data = StockyardRcnStock::with('stockyardDetails', 'subAccount', 'shipperCompany')
            ->where('slug', $slug)
            ->first();

        $lot_number = $stockyard_rcn_data->lot_number;
        $stockyard_rcn_split_data = StockyardRcnStockMix::
            where('lot_number', $lot_number)
            ->first();

        $stockyard = $stockyard_rcn_split_data->stockyard;
        $lot_numbers = json_decode($stockyard);

        $sub_account = SubAccount::with('account')
            ->where('id', $stockyard_rcn_data->sub_account_id)
            ->first();

        $accounts = Account::get();
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock", 'name' => "Stockyard"], ['name' => "RCN Stock Details"],
        ];

        return view('stockyard.rcn-stock.show_rcn_stock_mix', compact('stockyard_rcn_data', 'accounts', 'sub_account', 'lot_numbers'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $stockyard_rcn_data = StockyardRcnStock::with('stockyardDetails', 'subAccount', 'outwardRCN')
            ->where('slug', $slug)
            ->first();
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock", 'name' => "Stockyard"], ['name' => "Edit RCN Stock"],
        ];

        $stockyards = Stockyard::get();
        $accounts = Account::get();
        $shipper_details = ShipperDetails::get();
        $rcn_marks = Config::get('constants.rcn_marks');
        $disable_edit = $stockyard_rcn_data->outwardRCN->count() ? 1 : 0;

        $sub_account = SubAccount::with('account')
            ->where('id', $stockyard_rcn_data->sub_account_id)
            ->first();

        return view('stockyard.rcn-stock.edit_rcn_stock', compact('stockyard_rcn_data', 'sub_account', 'stockyards', 'accounts', 'shipper_details', 'rcn_marks', 'disable_edit'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    public function edit_split($slug)
    {
        $stockyard_rcn_data = StockyardRcnStock::with('stockyardDetails', 'subAccount', 'outwardRCN')
            ->where('slug', $slug)
            ->first();
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock", 'name' => "Stockyard"], ['name' => "Edit RCN Stock"],
        ];

        $stockyards = Stockyard::get();
        $accounts = Account::get();
        $shipper_details = ShipperDetails::get();
        $rcn_marks = Config::get('constants.rcn_marks');
        $disable_edit = $stockyard_rcn_data->outwardRCN->count() ? 1 : 0;
        $stockyard_rcn_split_data = StockyardRcnStockSplit::
            where('slug', $slug)
            ->first();
        $stockyard = $stockyard_rcn_split_data->stockyard;
        $parent = StockyardRcnStock::where('slug', $stockyard)->first();
        $parent_lot_number = $parent->lot_number;

        $sub_account = SubAccount::with('account')
            ->where('id', $stockyard_rcn_data->sub_account_id)
            ->first();

        return view('stockyard.rcn-stock.edit_rcn_stock_split', compact('stockyard_rcn_data', 'sub_account', 'stockyards', 'accounts', 'shipper_details', 'rcn_marks', 'disable_edit', 'parent_lot_number'), [
            'breadcrumbs' => $breadcrumbs]);
    }
    public function edit_compine($slug)
    {
        $stockyard_rcn_data = StockyardRcnStock::with('stockyardDetails', 'subAccount', 'outwardRCN')
            ->where('slug', $slug)
            ->first();
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock", 'name' => "Stockyard"], ['name' => "Edit RCN Stock"],
        ];
        $lot_number = $stockyard_rcn_data->lot_number;
        $stockyards = Stockyard::get();
        $accounts = Account::get();
        $shipper_details = ShipperDetails::get();
        $rcn_marks = Config::get('constants.rcn_marks');
        $disable_edit = $stockyard_rcn_data->outwardRCN->count() ? 1 : 0;
        $stockyard_rcn_split_data = StockyardRcnStockCombine::
            where('lot_number', $lot_number)
            ->first();

        $stockyard = $stockyard_rcn_split_data->stockyard;

        $lot_numbers = json_decode($stockyard);

        $sub_account = SubAccount::with('account')
            ->where('id', $stockyard_rcn_data->sub_account_id)
            ->first();

        return view('stockyard.rcn-stock.edit_rcn_stock_compine', compact('stockyard_rcn_data', 'sub_account', 'stockyards', 'accounts', 'shipper_details', 'rcn_marks', 'disable_edit', 'lot_numbers'), [
            'breadcrumbs' => $breadcrumbs]);
    }
    public function edit_mix($slug)
    {
        $stockyard_rcn_data = StockyardRcnStock::with('stockyardDetails', 'subAccount', 'outwardRCN')
            ->where('slug', $slug)
            ->first();
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "/stockyard/rcn-stock", 'name' => "Stockyard"], ['name' => "Edit RCN Stock"],
        ];
        $lot_number = $stockyard_rcn_data->lot_number;
        $stockyards = Stockyard::get();
        $accounts = Account::get();
        $shipper_details = ShipperDetails::get();
        $rcn_marks = Config::get('constants.rcn_marks');
        $disable_edit = $stockyard_rcn_data->outwardRCN->count() ? 1 : 0;
        $stockyard_rcn_split_data = StockyardRcnStockMix::
            where('lot_number', $lot_number)
            ->first();

        $stockyard = $stockyard_rcn_split_data->stockyard;

        $lot_numbers = json_decode($stockyard);

        $sub_account = SubAccount::with('account')
            ->where('id', $stockyard_rcn_data->sub_account_id)
            ->first();

        return view('stockyard.rcn-stock.edit_rcn_stock_mix', compact('stockyard_rcn_data', 'sub_account', 'stockyards', 'accounts', 'shipper_details', 'rcn_marks', 'disable_edit', 'lot_numbers'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [];
        $stockyard_rcn_stock = StockyardRcnStock::find($id);
        $validator_messages = [
            'stockyard_slug.required' => 'Please select a stockyard',
            'account_id.required' => 'Please select an account',
            'sub_account_id.required' => 'Please select a sub account',
            'lot_number.required' => 'Please enter lot number',
            'rcn_mark.required' => 'Please select a RCN mark',
            'shipper_company_slug.required' => 'Please select a shipper company',
            'be_number.required' => 'Please enter BE number',
            'bl_number.required' => 'Please enter BL number',
            'invoice_number.required' => 'Please enter invoice number',
            'bl_despatched_rcn_weight.required' => 'Please enter dispatched RCN weight',
            'bl_despatched_rcn_bags.required' => 'Please enter dispatched RCN bags',
            'out_turn.required' => 'Please enter out turn',
            'nut_count.required' => 'Please enter nut count',
            'rejection.required' => 'Please enter rejection',
            'warehouse_slug.required' => 'Please select a warehouse',
            'total_containers.required' => 'Please enter no. of containers',
            'balance_rcn_stock.required' => 'Please enter RCN weight',
            'balance_rcn_bag.required' => 'Please enter RCN bags',
            'balance_rcn_stock.min' => 'RCN weight mismatch with outwards',
            'balance_rcn_bag.min' => 'RCN bags mismatch with outwards',
        ];
        $validator_conditions = [
            'account_id' => 'required',
            'sub_account_id' => 'required',
            'lot_number' => 'required',
            'rcn_mark' => 'required',
            'shipper_company_slug' => 'required',
            'be_number' => 'required',
            'bl_number' => 'required',
            'invoice_number' => 'required',
            'out_turn' => 'required',
            'nut_count' => 'required',
            'rejection' => 'required',
            'warehouse_slug' => 'required',
        ];

        if (!$request->disable_edit) {
            $validator_conditions['bl_despatched_rcn_weight'] = 'required';
            $validator_conditions['bl_despatched_rcn_bags'] = 'required';
            $validator_conditions['total_containers'] = 'required';

        }
        if ($request->balance_rcn_stock) {
            $total_outward_rcn_stock = $stockyard_rcn_stock
                ->outwardRCN()
                ->sum('rcn_net_weight');
            $total_inward_rcn_stock = $stockyard_rcn_stock
                ->inwardRCN()
                ->sum('rcn_net_weight');
            $balance_rcn_net_weight = $total_outward_rcn_stock - $total_inward_rcn_stock;

            $validator_conditions['balance_rcn_stock'] = 'required|numeric|min:' . abs($balance_rcn_net_weight);
            $data['balance_rcn_stock'] = $request->balance_rcn_stock;
        }

        if ($request->balance_rcn_bag) {
            $total_outward_rcn_bag = $stockyard_rcn_stock
                ->outwardRCN()
                ->sum('rcn_bags');
            $total_inward_rcn_bag = $stockyard_rcn_stock
                ->inwardRCN()
                ->sum('rcn_bags');
            $balance_rcn_bag = $total_outward_rcn_bag - $total_inward_rcn_bag;
            $validator_conditions['balance_rcn_bag'] = 'required|integer|min:' . abs($balance_rcn_bag);
            $data['balance_rcn_bag'] = $request->balance_rcn_bag;
        }
        $data['account_slug'] = $request->account_id;
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {

            return \Redirect::back()->withErrors($validator)->withInput();
        } else {
            $stockyard_rcn_stock->update(array_merge($request->all(), $data));
            return redirect()->route("stockyard.rcn-stock")
                ->with('success', 'Stockyard RCN Stock updated successfully.');
        }
    }

    public function updateSplit(Request $request, $id)
    {

        $data = [];
        $stockyard_rcn_stock = StockyardRcnStock::find($id);


        if ($request->balance_rcn_stock) {
            $validator_conditions['balance_rcn_stock'] = 'required|numeric';
            $data['balance_rcn_stock'] = $request->balance_rcn_stock;
        }

        if ($request->balance_rcn_bag) {

            $validator_conditions['balance_rcn_bag'] = 'required|integer';
            $data['balance_rcn_bag'] = $request->balance_rcn_bag;
        }

        $validator = \Validator::make(request()->all(), $validator_conditions);

        if ($validator->fails()) {

            return \Redirect::back()->withErrors($validator)->withInput();
        } else {
            $slug_stock = $stockyard_rcn_stock->slug;
            $stockyard_rcn_stock->update(array_merge($request->all(), $data));
            return redirect()->route("stockyard.rcn-stock")
                ->with('success', 'Stockyard RCN Split Stock updated successfully.');
        }
    }

    public function updateCompine(Request $request, $id)
    {

        $data = [];
        $stockyard_rcn_stock = StockyardRcnStock::find($id);

   
        $stockyard_rcn_stock->balance_rcn_bag = $request->balance_rcn_bag;
        $stockyard_rcn_stock->balance_rcn_stock = $request->balance_rcn_stock;

        if ($request->balance_rcn_stock) {

            $validator_conditions['balance_rcn_stock'] = 'required|numeric';
            $data['balance_rcn_stock'] = $request->balance_rcn_stock;

        }

        if ($request->balance_rcn_bag) {

            $validator_conditions['balance_rcn_bag'] = 'required|integer';
            $data['balance_rcn_bag'] = $request->balance_rcn_bag;
        }

        $validator = \Validator::make(request()->all(), $validator_conditions);

        if ($validator->fails()) {

            return \Redirect::back()->withErrors($validator)->withInput();
        } else {

            $stockyard_rcn_stock->update();
            return redirect()->route("stockyard.rcn-stock")
                ->with('success', 'Stockyard RCN Compine Stock updated successfully.');
        }
    }

    public function updateMix(Request $request, $id)
    {

        $data = [];
        $stockyard_rcn_stock = StockyardRcnStock::find($id);

  
        $stockyard_rcn_stock->balance_rcn_bag = $request->balance_rcn_bag;
        $stockyard_rcn_stock->balance_rcn_stock = $request->balance_rcn_stock;


        if ($request->balance_rcn_stock) {

            $validator_conditions['balance_rcn_stock'] = 'required|numeric';
            $data['balance_rcn_stock'] = $request->balance_rcn_stock;

        }

        if ($request->balance_rcn_bag) {

            $validator_conditions['balance_rcn_bag'] = 'required|integer';
            $data['balance_rcn_bag'] = $request->balance_rcn_bag;
        }

        $validator = \Validator::make(request()->all(), $validator_conditions);

        if ($validator->fails()) {

            return \Redirect::back()->withErrors($validator)->withInput();
        } else {

            $stockyard_rcn_stock->update();
            return redirect()->route("stockyard.rcn-stock")
                ->with('success', 'Stockyard RCN Split Stock updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        StockyardRcnStock::find($id)->delete($id);
        return response()->json([
            "status" => 'success',
            "code" => 200]);
    }
}