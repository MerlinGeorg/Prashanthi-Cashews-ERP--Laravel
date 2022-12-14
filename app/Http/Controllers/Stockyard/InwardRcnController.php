<?php

namespace App\Http\Controllers\Stockyard;

use App\Http\Controllers\Controller;
use App\Models\Stockyard;
use App\Models\StockyardInwardRcn;
use App\Models\StockyardRcnStock;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;

class InwardRcnController extends Controller
{

    /**
     * Inward RCN.
     *
     */
    public function createInwardRcn()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Stockyard"], ['name' => "Inward RCN"],
        ];
        $stockyards = Stockyard::filterbyOffice()->get();

        $stockyard_rcn_stocks = null;

        if (Session::has('data.rcn_stock_slug')) {
            $stockyard_rcn_stocks = StockyardRcnStock::select('slug', 'stockyard_slug', 'account_lot_number')
                ->where('stockyard_slug', Session::get('data.stockyard_slug'))
                ->get();
        }

        return view('stockyard.inward-rcn.inward_rcn', compact('stockyards', 'stockyard_rcn_stocks'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    public function listStockyardRcnStocksForSplit(Request $request)
    {
        $stockyard_slug = $request->stockyard_slug;
       
        if(isset( $request->rcn_mark) ){

            $stockyard_rcn_stocks = StockyardRcnStock::where('stockyard_slug', $stockyard_slug)
          
         
            ->where(function($q) {
                $q->where('type','=','split')
                ->orWhere('type','=', 'mix')
                  ->orWhere('type', NULL);
            })
      

            ->filterbyOffice()->get();

        }else{

            $stockyard_rcn_stocks = StockyardRcnStock::where('stockyard_slug', $stockyard_slug)
            ->where(function($q) {
                $q->where('type','=','split')
                ->orWhere('type','=', 'mix')
                  ->orWhere('type', NULL);
            })
      
            ->filterbyOffice()->get();

        }
        return response()->json([
            'stockyard_rcn_stocks' => $stockyard_rcn_stocks,
        ]);
    }


    public function listStockyardRcnStocks(Request $request)
    {
        $stockyard_slug = $request->stockyard_slug;
       
        if(isset( $request->rcn_mark) ){

            $stockyard_rcn_stocks = StockyardRcnStock::where('stockyard_slug', $stockyard_slug)
        
            ->where(function($q) {
                $q->where('type','=','split')  
                  ->orWhere('type', NULL);
            })
      
            ->filterbyOffice()->get();

        }else{

            $stockyard_rcn_stocks = StockyardRcnStock::where('stockyard_slug', $stockyard_slug)
            ->where(function($q) {
                $q->where('type','=','split')
                  ->orWhere('type', NULL);
            })
            ->filterbyOffice()->get();

        }

        return response()->json([
            'stockyard_rcn_stocks' => $stockyard_rcn_stocks,
        ]);
    }

    public function addInwardRcn($stockyard_slug, $stockyardrcn_slug)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Stockyard"], ['name' => "Add Inward RCN"],
        ];

        $stockyard_rcn_data = StockyardRcnStock::select('lot_number')->
            where('slug', $stockyardrcn_slug)->first();

        return view('stockyard.inward-rcn.create_inward_rcn', compact('stockyard_slug', 'stockyardrcn_slug', 'stockyard_rcn_data'), ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveRcn(Request $request)
    {
        $validator_messages = $this->getValidationMessage();
        $validator_conditions = $this->getValidationCondition($request);
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        } else {
            $inward_rcn = new StockyardInwardRcn();
            $inward_rcn->document = '';
            if ($request->hasfile('document')) {
                $fileName = time() . '_' . $request->document->getClientOriginalName();
                $filePath = $request->document->move(public_path('uploads/inward-rcn'), $fileName);
                $inward_rcn->document = time() . '_' . $request->document->getClientOriginalName();
            }

            $inward_rcn->slug = $request->slug;
            $inward_rcn->stockyard_slug = isset($request->stockyard_slug) ? $request->stockyard_slug : '';
            $inward_rcn->stockyard_rcn_stock_slug = isset($request->stockyard_rcn_stock_slug) ? $request->stockyard_rcn_stock_slug : '';
            $inward_rcn->truck_reg_number = isset($request->truck_reg_number) ? $request->truck_reg_number : '';
            $inward_rcn->container_number = isset($request->container_number) ? $request->container_number : '';
            $inward_rcn->seal_number = isset($request->seal_number) ? $request->seal_number : '';
            $inward_rcn->dc_number = isset($request->dc_number) ? $request->dc_number : '';
            $inward_rcn->ewb_number = isset($request->ewb_number) ? $request->ewb_number : '';
            $inward_rcn->rcn_bags = isset($request->rcn_bags) ? $request->rcn_bags : '';
            $inward_rcn->rcn_net_weight = isset($request->rcn_net_weight) ? $request->rcn_net_weight : '';
            $inward_rcn->tare_weight = isset($request->tare_weight) ? $request->tare_weight : '';
            $inward_rcn->status = isset($request->status) ? $request->status : '';
            $inward_rcn->moisture_level = isset($request->moisture_level) ? $request->moisture_level : '';
            $inward_rcn->dispatched_date_time = $request->dispatched_date_time;
            $inward_rcn->received_date_time = $request->received_date_time;
            $inward_rcn->contact_number = isset($request->contact_number) ? $request->contact_number : '';
            $inward_rcn->out_turn = isset($request->out_turn) ? $request->out_turn : '';
            $inward_rcn->nut_count = isset($request->nut_count) ? $request->nut_count : '';
            $inward_rcn->rejection = isset($request->rejection) ? $request->rejection : '';
            $inward_rcn->save();

            if ($request->status == 2) {
                $stockyard_rcn_stock = StockyardRcnStock::whereSlug($inward_rcn->stockyard_rcn_stock_slug)
                    ->first();
                $stockyard_rcn_stock->balance_rcn_stock += $inward_rcn->rcn_net_weight;
                $stockyard_rcn_stock->balance_rcn_bag += $inward_rcn->rcn_bags;
                $stockyard_rcn_stock->save();
            }

            return Redirect::to("stockyard/inward-rcn")
                ->with('data', array(
                    'success' => 'Inward RCN added successfully.',
                    'stockyard_slug' => $inward_rcn->stockyard_slug,
                    'rcn_stock_slug' => $inward_rcn->stockyard_rcn_stock_slug)
                );

        }
    }

    /**
     * Stockyard RCN Stock List.
     *
     * @return \Illuminate\Http\Response
     */

    public function listInwardRcn(Request $request, $stockyard, $stockyardrcn)
    {  
        if ($request->ajax()) {
            $inwardRcnData = StockyardInwardRcn::where('stockyard_inward_rcns.stockyard_slug', $stockyard)
                ->where('stockyard_inward_rcns.stockyard_rcn_stock_slug', $stockyardrcn)
        
                ->get();
            return Datatables::of($inwardRcnData)
                ->addColumn('status', function ($row) {
                    return \Helper::inWardOutWardStatusWithBadge($row->status);
                })
                ->addColumn('action', function ($row) {
                    return [
                        'view' => \Helper::userAccess('stockyard-inward-rcn-view'),
                        'edit' => (\Helper::userAccess('stockyard-inward-rcn-edit')),
                        'delete' => \Helper::userAccess('stockyard-inward-rcn-delete'),
                    ];
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function viewRcn($slug)
    {
        $inward_rcn_data = StockyardInwardRcn::where('slug', $slug)
            ->first();
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Stockyard"], ['name' => "Inward RCN Details"],
        ];

        return view('stockyard.inward-rcn.show_inward_rcn', compact('inward_rcn_data'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function editRcn(Request $request, $slug)
    {
        $inward_rcn_data = StockyardInwardRcn::where('slug', $slug)
            ->first();

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Stockyard"], ['name' => "Edit Inward RCN"],
        ];

        return view('stockyard.inward-rcn.edit_inward_rcn', compact('inward_rcn_data'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function updateRcn(Request $request, $id)
    {
        $id = decrypt($id);
        $inward_rcn = StockyardInwardRcn::find($id);
        $previous_inward_rcn['rcn_bags'] = $inward_rcn->rcn_bags;
        $previous_inward_rcn['rcn_net_weight'] = $inward_rcn->rcn_net_weight;

        $validator_messages = $this->getValidationMessage();
        $validator_conditions = $this->getValidationCondition($request);
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        } else {
            if ($request->hasfile('document')) {
                $fileName = time() . '_' . $request->document->getClientOriginalName();
                $filePath = $request->document->move(public_path('uploads/inward-rcn'), $fileName);

                $inward_rcn->document = time() . '_' . $request->document->getClientOriginalName();
            }
            $inward_rcn->slug = isset($request->slug) ? $request->slug : '';
            $inward_rcn->stockyard_slug = isset($request->stockyard_slug) ? $request->stockyard_slug : '';
            $inward_rcn->stockyard_rcn_stock_slug = isset($request->stockyard_rcn_stock_slug) ? $request->stockyard_rcn_stock_slug : '';
            $inward_rcn->truck_reg_number = isset($request->truck_reg_number) ? $request->truck_reg_number : '';
            $inward_rcn->container_number = isset($request->container_number) ? $request->container_number : '';
            $inward_rcn->seal_number = isset($request->seal_number) ? $request->seal_number : '';
            $inward_rcn->dc_number = isset($request->dc_number) ? $request->dc_number : '';
            $inward_rcn->ewb_number = isset($request->ewb_number) ? $request->ewb_number : '';
            $inward_rcn->rcn_bags = isset($request->rcn_bags) ? $request->rcn_bags : '';
            $inward_rcn->rcn_net_weight = isset($request->rcn_net_weight) ? $request->rcn_net_weight : '';
            $inward_rcn->tare_weight = isset($request->tare_weight) ? $request->tare_weight : '';
            $inward_rcn->status = isset($request->status) ? $request->status : '';
            $inward_rcn->moisture_level = isset($request->moisture_level) ? $request->moisture_level : '';
            $inward_rcn->dispatched_date_time = $request->dispatched_date_time;
            $inward_rcn->received_date_time = $request->received_date_time;
            $inward_rcn->contact_number = isset($request->contact_number) ? $request->contact_number : '';
            $inward_rcn->out_turn = isset($request->out_turn) ? $request->out_turn : '';
            $inward_rcn->nut_count = isset($request->nut_count) ? $request->nut_count : '';
            $inward_rcn->rejection = isset($request->rejection) ? $request->rejection : '';
            $inward_rcn->save();

            if ($request->status == 2) {
                $stockyard_rcn_stock = StockyardRcnStock::whereSlug($inward_rcn->stockyard_rcn_stock_slug)
                    ->first();
                $stockyard_rcn_stock->balance_rcn_stock += $inward_rcn->rcn_net_weight;
                $stockyard_rcn_stock->balance_rcn_bag += $inward_rcn->rcn_bags;
                $stockyard_rcn_stock->save();

            }
            return Redirect::to("stockyard/inward-rcn")
                ->with(array(
                    'success' => 'Inward RCN updated successfully.',
                    'data' => array(
                        'stockyard_slug' => $inward_rcn->stockyard_slug,
                        'rcn_stock_slug' => $inward_rcn->stockyard_rcn_stock_slug),
                )
                );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function deleteRcn($id)
    {

        StockyardInwardRcn::find($id)->delete();
        return response()->json([
            "status" => 'success',
            "code" => 200]);
    }

    /**
     * Commman Validation Condition Array for Store and Update
     *@return Array
     */
    public function getValidationCondition($request): array
    {
        $validator_conditions = [
            'truck_reg_number' => 'required',
            'container_number' => 'required',
            'seal_number' => 'required',
            /*'dc_number' => 'required',
            'ewb_number' => 'required',*/
            'rcn_bags' => 'required',
            'rcn_net_weight' => 'required',
            'tare_weight' => 'required',
            'status' => 'required',
            'moisture_level' => 'required',
            'contact_number' => 'required',
            'out_turn' => 'required',
            'nut_count' => 'required',
            'rejection' => 'required',
            //'document' => 'required|mimes:pdf,xlx,csv,doc,docx|max:2048'
        ];
        if ($request->status == 1) {
            $validator_conditions['dispatched_date_time'] = 'required';
        } else if ($request->status == 2) {
            $validator_conditions['dispatched_date_time'] = 'required';
            $validator_conditions['received_date_time'] = 'required';
        }

        return $validator_conditions;

    }

    /**
     * Commman Validation Message Array for Store and Update
     *@return Array
     */
    public function getValidationMessage(): array
    {
        return [
            'truck_reg_number.required' => 'Please enter truck reg no.',
            'container_number.required' => 'Please enter container no.',
            'seal_number.required' => 'Please enter seal no.',
            'dc_number.required' => 'Please enter DC no.',
            'ewb_number.required' => 'Please enter EWB no.',
            'rcn_bags.required' => 'Please enter RCN bag count',
            'rcn_net_weight.required' => 'Please enter RCN net weight',
            'tare_weight.required' => 'Please enter tare weight',
            'status.required' => 'Please select a status',
            'moisture_level.required' => 'Please select a moisture level',
            'dispatched_date_time.required' => 'Please select dispatched date and time',
            'received_date_time.required' => 'Please select received date and time',
            'contact_number.required' => 'Please enter contact no.',
            'out_turn.required' => 'Please enter out turn',
            'nut_count.required' => 'Please enter net count',
            'rejection.required' => 'Please enter rejection',
            //'document.required' => 'Please upload document',
        ];
    }
}