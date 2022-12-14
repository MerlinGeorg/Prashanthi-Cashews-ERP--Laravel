<?php

namespace App\Http\Controllers\Factory;

use App\Http\Controllers\Controller;

use App\Models\FactoryRcnStock;
use DataTables;
use Illuminate\Http\Request;

class FactoryRcnStockController extends Controller
{

    /**
     * Outward RCN.
     *
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "factory/rcn-stock", 'name' => "Factory"], ['name' => "RCN Stock"],
        ];

        return view('factory.factory-rcn-stock.list_factory_rcn_stock', [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Outward RCN List.
     *
     * @return \Illuminate\Http\Response
     */

    public function listFactoryRcnStocks(Request $request)
    {
        if ($request->ajax()) {
            $factoryRcnStockData = FactoryRcnStock::with('stockyardRcnDetails', 'factoryDetails')->filterbyOffice()->get();

            return Datatables::of($factoryRcnStockData)
                ->addIndexColumn()
                ->addColumn('stockyard_lot_number', function (FactoryRcnStock $factory) {
                    return $factory->stockyardRcnDetails->lot_number;
                })
                ->addColumn('factory_name', function (FactoryRcnStock $factory) {
                    return $factory->factoryDetails->factory_name;
                })
                ->addColumn('action', function ($row) {
                    return [
                        'view' => \Helper::userAccess('factory-rcn-view'),
                        'edit' => \Helper::userAccess('factory-rcn-edit'),
                        'delete' => \Helper::userAccess('factory-rcn-delete'),
                    ];
                })
                ->rawColumns(['action'])
                ->make(true);
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
        $factory_rcn_stock_data = FactoryRcnStock::with('stockyardRcnDetails', 'factoryDetails')->where('stockyard_rcn_stock_slug', $slug)->first();
        //dd($factory_rcn_stock_data);
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "factory/rcn-stock", 'name' => "Factory"], ['name' => "Factory RCN Stock Details"],
        ];

        return view('factory.factory-rcn-stock.show_factory_rcn_stock', compact('factory_rcn_stock_data'), [
            'breadcrumbs' => $breadcrumbs]);
    }

}