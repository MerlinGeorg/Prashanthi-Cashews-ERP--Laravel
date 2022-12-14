<?php

namespace App\Http\Controllers\Stockyard;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\FactoryRcnInward;
use App\Models\Stockyard;
use App\Models\StockyardOutwardRcn;
use App\Models\StockyardRcnStock;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OutwardRcnController extends Controller
{

    /**
     * Outward RCN.
     *
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Stockyard"], ['name' => "Outward RCN"],
        ];

        return view('stockyard.outward-rcn.list_outward_rcn', [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Outward RCN List.
     *
     * @return \Illuminate\Http\Response
     */

    public function listStockyardOutwardRcn(Request $request)
    {
        if ($request->ajax()) {
            $stockyardOutwardRcnData = StockyardOutwardRcn::with('factory', 'stockyardRcnStockDetails')
                ->filterbyOffice()
                ->latest();

            return Datatables::eloquent($stockyardOutwardRcnData)
            // ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return \Helper::inWardOutWardStatusWithBadge($row->status);
                })
                ->editColumn('stockyard_rcn_stock_details.lot_number', function ($row) {

                    if ($row->borrowed_lot_number == null) {
                        return strtoupper($row->stockyard_rcn_stock_slug);

                    } else {
                        return strtoupper($row->stockyard_rcn_stock_slug) . \Helper::rcnBorrowWithBadge() . "/" . strtoupper($row->borrowed_lot_number);
                    }
                })

                ->addColumn('action', function ($row) {
                    return [
                        'view' => \Helper::userAccess('stockyard-outward-rcn-view'),
                        'edit' => \Helper::userAccess('stockyard-outward-rcn-edit') && $row->status != 2,
                        'delete' => \Helper::userAccess('stockyard-outward-rcn-delete'),
                    ];
                })
                ->rawColumns(['status', 'action', 'stockyard_rcn_stock_details.lot_number'])
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
            ['link' => "/", 'name' => "Home"], ['link' => "stockyard/outward-rcn", 'name' => "Stockyard"], ['name' => "Add Outward RCN"],
        ];

        $stockyard_rcn_stocks = StockyardRcnStock::select("lot_number", "slug")->filterbyOffice()->orderBy('id', 'DESC')->get();
        $factories = Factory::filterbyOffice()->get();

        return view('stockyard.outward-rcn.create_outward_rcn', compact('stockyard_rcn_stocks', 'factories'), ['breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $stockyard_rcn_stock = StockyardRcnStock::where('slug', $request->stockyard_rcn_stock_slug)
            ->first();

        $validator_messages = $this->getValidationMessage();
        $validator_conditions = $this->getValidationCondition($request, $stockyard_rcn_stock);
        $validator_conditions['stockyard_rcn_stock_slug'] = 'required';

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        } else {
            $outward_rcn = new StockyardOutwardRcn();

            if ($request->hasfile('document')) {
                $fileName = time() . '_' . $request->document->getClientOriginalName();
                $filePath = $request->document->move(public_path('uploads/outward-rcn'), $fileName);
                $outward_rcn->document = time() . '_' . $request->document->getClientOriginalName();
            }

            $outward_rcn->slug = isset($request->slug) ? $request->slug : '';
            $outward_rcn->stockyard_rcn_stock_slug = isset($request->stockyard_rcn_stock_slug) ? $request->stockyard_rcn_stock_slug : '';
            $outward_rcn->factory_slug = isset($request->factory_slug) ? $request->factory_slug : '';
            $outward_rcn->truck_reg_number = isset($request->truck_reg_number) ? $request->truck_reg_number : '';
            $outward_rcn->dc_number = isset($request->dc_number) ? $request->dc_number : '';
            $outward_rcn->ewb_number = isset($request->ewb_number) ? $request->ewb_number : '';
            $outward_rcn->rcn_bags = isset($request->rcn_bags) ? $request->rcn_bags : 0;
            $outward_rcn->rcn_net_weight = isset($request->rcn_net_weight) ? $request->rcn_net_weight : 0;
            $outward_rcn->tare_weight = isset($request->tare_weight) ? $request->tare_weight : '';
            $outward_rcn->status = isset($request->status) ? $request->status : 0;
            $outward_rcn->moisture_level = isset($request->moisture_level) ? $request->moisture_level : '';
            $outward_rcn->contact_number = isset($request->contact_number) ? $request->contact_number : '';
            $outward_rcn->out_turn = $request->out_turn;
            $outward_rcn->nut_count = $request->nut_count;
            $outward_rcn->rejection = $request->rejection;
            $outward_rcn->dispatched_date_time = $request->dispatched_date_time;
            $outward_rcn->borrowed_lot_number = $request->borrowed_lot_number;
            $outward_rcn->save();

            if ($outward_rcn->id) {
                $stockyard_rcn_stock->balance_rcn_stock -= $request->rcn_net_weight;
                $stockyard_rcn_stock->balance_rcn_bag -= $request->rcn_bags;
                $stockyard_rcn_stock->save();
                //Saving Data to Factory Inward Table On Status Update - Dispatch(1)
                $status = $request->status;
                if ($status == 1) {
                    $this->updateFactoryInward($request, $outward_rcn);
                }
            }

            return Redirect::to('stockyard/outward-rcn')->with('success', 'Outward RCN added successfully.');
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
        $outward_rcn_data = StockyardOutwardRcn::where('slug', $slug)
            ->first();
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "stockyard/outward-rcn", 'name' => "Stockyard"], ['name' => "Outward RCN Details"],
        ];

        return view('stockyard.outward-rcn.show_outward_rcn', compact('outward_rcn_data'), [
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
        $outward_rcn_data = StockyardOutwardRcn::with('stockyardRcnStockDetails')
            ->where('slug', $slug)
            ->first();

        //$stockyard_rcn_stocks = StockyardRcnStock::get();
        $stockyard_rcn_stocks = StockyardRcnStock::select("lot_number", "slug")->orderBy('id', 'DESC')->get();

        $factories = Factory::get();

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "stockyard/outward-rcn", 'name' => "Stockyard"], ['name' => "Edit Outward RCN"],
        ];

        return view('stockyard.outward-rcn.edit_outward_rcn', compact('outward_rcn_data', 'stockyard_rcn_stocks', 'factories'), [
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

        $outward_rcn = StockyardOutwardRcn::find($id);
        $previous_outward_rcn = array();
        $previous_outward_rcn['rcn_bags'] = $outward_rcn->rcn_bags;
        $previous_outward_rcn['rcn_net_weight'] = $outward_rcn->rcn_net_weight;

        $stockyard_rcn_stock = StockyardRcnStock::where('slug', $outward_rcn->stockyard_rcn_stock_slug)
            ->first();

        /// Temp Stockyad rcn(restoring) only for validation  -Start
        $temp_stockyard_rcn_stock = clone $stockyard_rcn_stock;
        $temp_stockyard_rcn_stock->balance_rcn_stock += $outward_rcn->rcn_net_weight;
        $temp_stockyard_rcn_stock->balance_rcn_bag += $outward_rcn->rcn_bags;
        /// Temp Stockyad rcn(restoring) only for validation -end
        $validator_messages = $this->getValidationMessage();
        $validator_conditions = $this->getValidationCondition($request, $temp_stockyard_rcn_stock);

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        } else {
            if ($request->hasfile('document')) {
                $fileName = time() . '_' . $request->document->getClientOriginalName();
                $filePath = $request->document->move(public_path('uploads/outward-rcn'), $fileName);

                $outward_rcn->document = time() . '_' . $request->document->getClientOriginalName();
            }

            $outward_rcn->slug = isset($request->slug) ? $request->slug : '';
            //$outward_rcn->stockyard_rcn_stock_slug = isset($request->stockyard_rcn_stock_slug) ? $request->stockyard_rcn_stock_slug : '';
            $outward_rcn->factory_slug = isset($request->factory_slug) ? $request->factory_slug : '';
            $outward_rcn->truck_reg_number = isset($request->truck_reg_number) ? $request->truck_reg_number : '';
            $outward_rcn->dc_number = isset($request->dc_number) ? $request->dc_number : '';
            $outward_rcn->ewb_number = isset($request->ewb_number) ? $request->ewb_number : '';
            $outward_rcn->rcn_bags = isset($request->rcn_bags) ? $request->rcn_bags : 0;
            $outward_rcn->rcn_net_weight = isset($request->rcn_net_weight) ? $request->rcn_net_weight : 0;
            $outward_rcn->tare_weight = isset($request->tare_weight) ? $request->tare_weight : '';
            $outward_rcn->status = $request->status;
            $outward_rcn->moisture_level = isset($request->moisture_level) ? $request->moisture_level : '';
            $outward_rcn->dispatched_date_time = $request->dispatched_date_time;
            $outward_rcn->received_date_time = $request->received_date_time;
            $outward_rcn->contact_number = isset($request->contact_number) ? $request->contact_number : '';
            $outward_rcn->out_turn = $request->out_turn;
            $outward_rcn->nut_count = $request->nut_count;
            $outward_rcn->rejection = $request->rejection;

            $outward_rcn->borrowed_lot_number = $request->borrowed_lot_number;
            $outward_rcn->save();

            if ($outward_rcn->id) {
                $balance_rcn_stock = 0;
                $balance_rcn_bags = 0;
                if ($previous_outward_rcn['rcn_net_weight'] != $outward_rcn->rcn_net_weight) {

                    $balance_rcn_stock = $previous_outward_rcn['rcn_net_weight'] > 0 ?
                    $previous_outward_rcn['rcn_net_weight'] - $outward_rcn->rcn_net_weight :
                    $outward_rcn->rcn_net_weight;

                }
                if ($previous_outward_rcn['rcn_bags'] != $outward_rcn->rcn_bags) {

                    $balance_rcn_bags = $previous_outward_rcn['rcn_bags'] > 0 ?
                    $previous_outward_rcn['rcn_bags'] - $outward_rcn->rcn_bags :
                    $outward_rcn->rcn_bags;
                }

                if ($balance_rcn_bags || $balance_rcn_stock) {

                    $stockyard_rcn_stock->balance_rcn_stock += $balance_rcn_stock;
                    $stockyard_rcn_stock->balance_rcn_bag += $balance_rcn_bags;
                    $stockyard_rcn_stock->save();

                }

                if ($request->status == 1) {
                    $this->updateFactoryInward($request, $outward_rcn);
                }

            }
            return Redirect::to('stockyard/outward-rcn')->with('success', 'Outward RCN updated successfully.');
        }
    }

    public function updateFactoryInward($request, $outward_rcn)
    {
        $factory_details = Factory::where('slug', $request->factory_slug)->first();

        if (!$factory_rcn_inward = FactoryRcnInward::where('outward_id', $outward_rcn->id)->first()) {
            $factory_rcn_inward = new FactoryRcnInward();
        }

        $factory_rcn_inward->slug = "IN-" . $outward_rcn->slug;
        $factory_rcn_inward->outward_id = isset($outward_rcn->id) ? $outward_rcn->id : '';
        $factory_rcn_inward->factory_slug = isset($outward_rcn->factory_slug) ? $outward_rcn->factory_slug : '';
        $factory_rcn_inward->dc_number = isset($outward_rcn->dc_number) ? $outward_rcn->dc_number : '';
        $factory_rcn_inward->ewb_number = isset($outward_rcn->ewb_number) ? $outward_rcn->ewb_number : '';
        $factory_rcn_inward->rcn_bags = '';

        //  $factory_rcn_inward->borrowed_lot_number =  $request->borrowed_lot_number??null;
        $factory_rcn_inward->rcn_net_weight = '';
        $factory_rcn_inward->tare_weight = 0;
        $factory_rcn_inward->save();

        return $factory_rcn_inward;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stockyard_outward = StockyardOutwardRcn::select('rcn_bags', 'rcn_net_weight', 'stockyard_rcn_stock_slug')
            ->find($id);
        $stockyard_rcn_stock = StockyardRcnStock::whereSlug($stockyard_outward->stockyard_rcn_stock_slug)
            ->first();
        $stockyard_rcn_stock->balance_rcn_stock += $stockyard_outward->rcn_net_weight;
        $stockyard_rcn_stock->balance_rcn_bag += $stockyard_outward->rcn_bags;

        $stockyard_rcn_stock->save();
        StockyardOutwardRcn::find($id)->delete();

        return response()->json([
            "status" => 'success',
            "code" => 200]);
    }

    /**
     * Commman Validation Condition Array for Store and Update
     *@return Array
     */
    public function getValidationCondition($request, $stockyard_rcn_stock): array
    {
        $validator_conditions = [
            'factory_slug' => 'required',
            'truck_reg_number' => 'required',
            'dc_number' => 'required',
            'ewb_number' => 'required',
            'rcn_bags' => 'required',
            'rcn_net_weight' => 'required',
            'tare_weight' => 'required',
            'status' => 'required',
            'moisture_level' => 'required',
            'contact_number' => 'required',

        ];

        if ($stockyard_rcn_stock) {
            $validator_conditions['rcn_bags'] = 'required|integer|max:' . $stockyard_rcn_stock->balance_rcn_bag;
            $validator_conditions['rcn_net_weight'] = 'required|numeric|max:' . $stockyard_rcn_stock->balance_rcn_stock;
        }

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
            'stockyard_rcn_stock_slug.required' => 'Please select RCN lot number',
            'factory_slug.required' => 'Please select factory',
            'truck_reg_number.required' => 'Please enter truck registration number',
            'dc_number.required' => 'Please enter DC number',
            'ewb_number.required' => 'Please enter EWB number',
            'rcn_bags.required' => 'Please enter RCN bag count',
            'rcn_bags.max' => 'Insufficient Stock ',
            'rcn_net_weight.required' => 'Please enter RCN net weight',
            'rcn_net_weight.max' => 'Insufficient Stock',
            'tare_weight.required' => 'Please enter tare weight',
            'status.required' => 'Please select a status',
            'moisture_level.required' => 'Please select a moisture level',
            'dispatched_date_time.required' => 'Please select dispatched date and time',
            'received_date_time.required' => 'Please select received date and time',
            'contact_number.required' => 'Please enter contact number',
            'document.required' => 'Please upload document',
        ];
    }

}