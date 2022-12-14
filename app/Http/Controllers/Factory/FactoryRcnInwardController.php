<?php

namespace App\Http\Controllers\Factory;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\FactoryRcnInward;
use App\Models\FactoryRcnStock;
use App\Models\StockyardOutwardRcn;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class FactoryRcnInwardController extends Controller
{

    /**
     * Outward RCN.
     *
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['name' => "RCN Inwards"],
        ];

        return view('factory.factory-rcn-inward.list_factory_rcn_inward', [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Outward RCN List.
     *
     * @return \Illuminate\Http\Response
     */

    public function listFactoryRcnInwards(Request $request)
    {
        if ($request->ajax()) {
            $factoryRcnInwardsData = FactoryRcnInward::with('outwardRcnDetails', 'Factory', 'outwardRcnDetails.stockyardRcnStockDetails')
                ->leftJoin('stockyard_outward_rcns', function ($join) {
                    $join->on('stockyard_outward_rcns.id', '=', 'factory_rcn_inwards.outward_id');
                })
                ->filterbyOffice()
            // ->leftJoin("stockyard_outward_rcns","stockyard_outward_rcns.id","factory_rcn_inwards.outward_id")
                ->get();

            return Datatables::of($factoryRcnInwardsData)
                ->addColumn('outward_rcn_slug', function (FactoryRcnInward $factory) {
                    return $factory->outwardRcnDetails->slug ?? '';
                })
                ->addColumn('truck_reg_number', function (FactoryRcnInward $factory) {
                    return $factory->outwardRcnDetails->truck_reg_number ?? '';
                })
                ->editColumn('outward_rcn_details.stockyard_rcn_stock_details.lot_number', function (FactoryRcnInward $row) {
                    //    dd($row->borrowed_lot_number);

                    if ($row->borrowed_lot_number != null) {

                        return strtoupper($row->outwardRcnDetails->stockyard_rcn_stock_slug) . \Helper::rcnBorrowWithBadge() . "/" . strtoupper($row->borrowed_lot_number);

                    } else {
                        return strtoupper($row->outwardRcnDetails->stockyard_rcn_stock_slug);
                    }
                })

                ->addColumn('status', function (FactoryRcnInward $factory) {

                    return \Helper::inWardOutWardStatusWithBadge($factory->outwardRcnDetails->status ?? 0);

                })
                ->addColumn('action', function (FactoryRcnInward $factory) {
                    return [
                        'view' => \Helper::userAccess('factory-inward-rcn-view'),
                        'edit' => \Helper::userAccess('factory-inward-rcn-edit') && $factory->outwardRcnDetails ?? 0 != 2,
                        'delete' => \Helper::userAccess('factory-inward-rcn-delete'),
                    ];
                })
                ->rawColumns(['action', 'status', "outward_rcn_details.stockyard_rcn_stock_details.lot_number"])
            // 'outward_rcn_details.stockyard_rcn_stock_details.lot_number'
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRcnStock(Request $request)
    {
        if ($request->outward_id) {
            $stockyardRcnStockData = StockyardOutwardRcn::with('stockyardRcnStockDetails')->where('id', $request->outward_id)->first();

            $response['result'] = $stockyardRcnStockData->toArray();
            return \Response::json($response);
        } else {
            return null;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $factory_rcn_inward_data = FactoryRcnInward::where('stockyard_outward_rcns.slug', $slug)
            ->leftJoin('stockyard_outward_rcns', function ($join) {
                $join->on('stockyard_outward_rcns.id', '=', 'factory_rcn_inwards.outward_id');
            })
            ->first();

        $outward_rcn_data = StockyardOutwardRcn::where('id', $factory_rcn_inward_data->outward_id)->first();

        $outward_rcn = StockyardOutwardRcn::get();
        $factories = Factory::get();

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "factory/factory-rcn-inward", 'name' => "Factory Rcn Inwards"], ['name' => "Edit Factory RCN Inward"],
        ];

        return view('factory.factory-rcn-inward.edit_factory_rcn_inward', compact('factory_rcn_inward_data', 'outward_rcn', 'factories', 'outward_rcn_data'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $factory_rcn_inward = FactoryRcnInward::with('outwardRcnDetails')
            ->leftJoin('stockyard_outward_rcns', function ($join) {
                $join->on('stockyard_outward_rcns.id', '=', 'factory_rcn_inwards.outward_id');
            })
            ->where('stockyard_outward_rcns.id', $id)->first();
        $status = $request->status;

        $validator_conditions = [
            'status' => 'required',
            'rcn_bags' => 'required_if:status,==,2|nullable|integer|max:' . $factory_rcn_inward->outwardRcnDetails->rcn_bags,
            'rcn_net_weight' => 'required_if:status,==,2|nullable|integer|max:' . $factory_rcn_inward->outwardRcnDetails->rcn_net_weight,

            'received_date_time' => $status == 2 ? 'required' : '',

        ];
        $validator_messages = [
            'rcn_bags.required_if' => 'The Rcn bag field is required when status is received',
            'rcn_net_weight.required_if' => 'The Rcn net weight field is required when status is received',
            'rcn_bags.required' => 'Please enter RCN bag count',
            'rcn_bags.max' => 'Insufficient bags ',
            'rcn_net_weight.required' => 'Please enter RCN net weight',
            'rcn_net_weight.max' => 'Insufficient Weight',
        ];

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        } else {

            $factory_rcn_inward->slug = isset($request->slug) ? $request->slug : '';
            $factory_rcn_inward->rcn_bags = isset($request->rcn_bags) ? $request->rcn_bags : '';
            $factory_rcn_inward->rcn_net_weight = isset($request->rcn_net_weight) ? $request->rcn_net_weight : '';
            $factory_rcn_inward->save();

            //Updating Outward RCN Table(For Status)
            $outward_rcn_data = StockyardOutwardRcn::where('id', $factory_rcn_inward->outward_id)->first();
            $outward_rcn_data->status = isset($status) ? $status : $status;
            $outward_rcn_data->received_date_time = $request->received_date_time;
            $outward_rcn_data->save();

            //Updating Factory Stocks Table
            if ($status == 2) {
                //Update new stock if same lot number available
                $factory_rcn_stock = FactoryRcnStock::where('stockyard_rcn_stock_slug',
                    $factory_rcn_inward->outwardRcnDetails->stockyard_rcn_stock_slug)
                    ->first();

                if (!$factory_rcn_stock) {

                    $factory_rcn_stock = new FactoryRcnStock();
                    $factory_rcn_stock->total_rcn_factory_stock = 0;
                    $factory_rcn_stock->total_rcn_bag = 0;
                    $factory_rcn_stock->balance_rcn_factory_stock = 0;
                    $factory_rcn_stock->balance_rcn_bag = 0;

                }

                $factory_rcn_stock->factory_slug = $factory_rcn_inward->factory_slug;
                $factory_rcn_stock->stockyard_rcn_stock_slug = isset($factory_rcn_inward->outwardRcnDetails->stockyard_rcn_stock_slug) ?
                $factory_rcn_inward->outwardRcnDetails->stockyard_rcn_stock_slug : '';
                $factory_rcn_stock->total_rcn_factory_stock += isset($request->rcn_net_weight) ?
                $request->rcn_net_weight : '';
                $factory_rcn_stock->total_rcn_bag += isset($request->rcn_bags) ?
                $request->rcn_bags : '';
                $factory_rcn_stock->balance_rcn_factory_stock += isset($request->rcn_net_weight) ?
                $request->rcn_net_weight : '';
                $factory_rcn_stock->balance_rcn_bag += isset($request->rcn_bags) ?
                $request->rcn_bags : '';

                $factory_rcn_stock->save();
            }

            return Redirect::to('factory/factory-rcn-inward')->with('success', 'Factory RCN Inward updated successfully.');
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
        $factory_rcn_inward_data = FactoryRcnInward::with('outwardRcnDetails', 'Factory', 'outwardRcnDetails.stockyardRcnStockDetails')
            ->leftJoin('stockyard_outward_rcns', function ($join) {
                $join->on('stockyard_outward_rcns.id', '=', 'factory_rcn_inwards.outward_id');
            })
            ->where('stockyard_outward_rcns.slug', $slug)->first();

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "factory/factory-rcn-inward", 'name' => "Factory RCN Inwards"], ['name' => "Factory RCN Inward"],
        ];

        return view('factory.factory-rcn-inward.show_factory_rcn_inward', compact('factory_rcn_inward_data'), [
            'breadcrumbs' => $breadcrumbs]);
    }

}