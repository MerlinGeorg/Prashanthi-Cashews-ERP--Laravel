<?php

namespace App\Http\Controllers\Factory;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\FactoryBorma;
use App\Models\FactoryCutting;
use DataTables;
use Illuminate\Http\Request;

class BormaStockController extends Controller
{

    /**
     * Borma Stock.
     *
     */
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "factory/borma", 'name' => "Factory"], ['name' => "Borma Stock"],
        ];

        return view('factory.borma.list', [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Borma RCN List.
     *
     * @return \Illuminate\Http\Response
     */

    public function listBormaStocks(Request $request)
    {
        if ($request->ajax()) {
            $bormaStockData = FactoryBorma::with('factoryDetails')->filterbyOffice()->get();

            return Datatables::of($bormaStockData)
                ->addColumn('factory_name', function (FactoryBorma $borma) {
                    return $borma->factoryDetails->factory_name;
                })
                ->addColumn('action', function ($row) {
                    return [
                        'view' => \Helper::userAccess('factory-borma-view'),
                        'edit' => \Helper::userAccess('factory-borma-edit'),
                        'delete' => \Helper::userAccess('factory-borma-delete'),
                    ];
                })
                ->rawColumns(['action'])
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
            ['link' => "/", 'name' => "Home"], ['link' => "/factory/borma", 'name' => "Factory"], ['name' => "Add Borma Stock"],
        ];

        $factories = Factory::get();

        return view('factory.borma.create', compact('factories'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    public function generateWorkNumber()
    {
        $serial_obj = FactoryBorma::select('borma_work_number')->latest('id')->first();

        if ($serial_obj) {
            $serial_number = $serial_obj->borma_work_number;
            $serial_number_parts = explode('-', $serial_number);
            $removed_char = substr($serial_number_parts[1], 1);

            $generated_serial_number = 'BWORKNO' . '-' . $stpad = str_pad((int) $removed_char + 1, 5, "0", STR_PAD_LEFT);
        } else {
            $generated_serial_number = 'BWORKNO' . '-' . str_pad(1, 5, "0", STR_PAD_LEFT);
        }
        //TODO - Reset Work Number
        return $generated_serial_number;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $cutting_slugs = array_column($request->cutting_stocks, 'cutting_slug');

        $factory_cutting_stocks = FactoryCutting::whereIn('slug', $cutting_slugs)->get();

        $validator_messages = $this->getValidationMessage();
        $validator_conditions = $this->getValidationRules($factory_cutting_stocks);

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {

            $data = $request->all();
            $data['stockyard_rcn_stock_slug'] = $request->stockyard_rcn_stock_slug;
            $data['borma_work_number'] = strtoupper($this->generateWorkNumber());
            $data['balance_wholes'] = $request->wholes;
            $data['balance_brokens'] = $request->brokens;
            $data['balance_piruwal'] = $request->piruwal;

            $insertData = FactoryBorma::create($data);

            // if ($insertData && $request->filled('cutting_stocks')) {
            //     $borma_slug = $insertData->slug;
            //     $this->insertBoilingSizering($borma_slug, $request->cutting_stocks);
            // }

            return redirect()->route('factory.borma')
                ->with('success', 'Borma stock created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FactoryBorma  $borma
     * @return \Illuminate\Http\Response
     */
    public function show(FactoryBorma $borma)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/borma", 'name' => "Borma Stock"], ['name' => "Borma Stock Details"],
        ];

        return view('factory.borma.show', compact('borma'), [
            'breadcrumbs' => $breadcrumbs]);

    }

    public function edit(FactoryBorma $borma)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/borma", 'name' => "Borma Stock"], ['name' => "Edit Borma Stock"],
        ];

        $factories = Factory::select('factory_name', 'slug')->get();

        $factory_sizers = FactorySizering::join('factories', 'factories.slug', '=', 'factory_sizering_stocks.factory_slug')
            ->select('factory_cutting_stocks.slug', 'cutting_number')
            ->where('factories.slug', '=', $boiling->factory_slug)
            ->where('factory_stock_slug', '=', $boiling->stockyard_rcn_stock_slug)
            ->availableSizering()
            ->get();

        return view('factory.boiling.edit', compact('boiling', 'factory_sizers'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PackageCenter  $sizering
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FactoryBoiling $boiling)
    {
        $sizering_slugs = array_column($request->sizering_stocks, 'sizering_slug');

        $factory_sizering_stocks = FactorySizering::whereIn('slug', $sizering_slugs)->get();

        $validator_messages = $this->getValidationMessage();
        $validator_conditions = $this->getValidationRules($factory_sizering_stocks, $boiling);

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $updateboiling = [];
            $total_boiling_weight = 0;

            foreach (Config::get('constants.grades') as $grade) {
                if ($request->filled("{$grade}_total_weight")) {
                    $total_boiling_weight +=
                    $updateboiling["{$grade}_total_weight"]
                    = $updateboiling["{$grade}_balance_weight"]
                    = $request->{"{$grade}_total_weight"};
                }
            }

            $updateboiling['total_boiling_weight'] = $total_boiling_weight;
            $updateboiling['balance_boiling_weight'] = $total_boiling_weight;
            $updateboiling['boiling_date_time'] = $request->boiling_date_time;

            $boiling->update($updateboiling);

            $this->updateBoilingSizering($boiling, $request->sizering_stocks);

            return redirect()->route('factory.boiling')
                ->with('success', 'Boiling Stock updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FactoryBoiling  $boiling
     * @return \Illuminate\Http\Response
     */
    public function destroy(FactoryBoiling $boiling)
    {
        $boiling_sizers = FactorySizeringBoilingMap::where('boiling_slug', '=', $boiling->slug);
        $boiling_sizerList = $boiling_sizers->get();
        if ($boiling_sizerList) {
            $this->deleteSizeringMaping($boiling_sizerList);
        }
        $boiling->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }

    /**
     * Commman Validation Rules Array for Store and Update
     *@return Array
     */
    public function getValidationRules($factory_borma_stocks, $boiling = null): array
    {
        $validator_conditions = [
            'factory_slug' => 'required|string|exists:factories,slug,deleted_at,NULL',
            'stockyard_rcn_stock_slug' => 'required|string|exists:factory_rcn_stocks,stockyard_rcn_stock_slug,deleted_at,NULL',
            'borma_work_date_time' => 'required|date|after_or_equal:today',
            'cutting_stocks' => 'required|array',
            // 'cuttings_stocks.*.cutting_slug' => 'required|string|distinct|exists:factory_cutting_stocks,slug,deleted_at,NULL',
        ];

        // foreach (Config::get('constants.grades') as $name => $grade) {
        //     $validator_conditions[$grade . '_total_weight'] = 'nullable|numeric|between:0,100000';
        //     $validator_conditions['sizering_stocks.*.' . $grade] = 'nullable|numeric|between:0,0';
        // }

        // $existing = [];
        // foreach ($boiling->boilingMap as $sizeringBoiling) {
        //     $existing[$sizeringBoiling->sizering_slug] = $sizeringBoiling;
        // }

        // $sizering_slugs = array_flip(array_unique(array_filter(array_column(request()->sizering_stocks, 'sizering_slug'))));

        // if ($factory_sizering_stocks->count()) {
        //     foreach ($factory_sizering_stocks as $sizering) {
        //         $index = $sizering_slugs[$sizering->slug];

        //         foreach (Config::get('constants.grades') as $grade) {
        //             if ($sizering->{"{$grade}_total_weight"}) {
        //                 $validator_conditions["sizering_stocks.{$index}.{$grade}"] = 'nullable|numeric|between:0,' . ($existing[$sizering->slug]->{$grade} ?? 0) + $sizering->{"{$grade}_balance_weight"};
        //             }
        //         }
        //     }
        // }

        return $validator_conditions;
    }

    /**
     * Commman Validation Message Array for Store and Update
     *@return Array
     */
    public function getValidationMessage(): array
    {
        $messages = [
            'factory_slug.required' => 'Please select a factory',
            'factory_slug.string' => 'Please enter valid factory',
            'stockyard_rcn_stock_slug.required' => 'Please select a lot number',
            'stockyard_rcn_stock_slug.string' => 'Please select a valid lot number',
            'borma_work_date_time.required' => 'Please select a valid date',
            'borma_work_date_time.date' => 'Please select a valid date',
            'borma_work_date_time.after_or_equal' => 'Please select a valid date',
            'cutting_stocks.*.cutting_slug.required' => 'Please select valid cutting stock',
            'cutting_stocks.*.cutting_slug.exists' => 'Please select valid cutting stock',
            'cutting_stocks.*.cutting_slug.distinct' => 'The selected cutting number already chosen',
        ];

        // foreach (Config::get('constants.grades') as $name => $grade) {
        //     $messages[$grade . '_total_weight.numeric'] = 'Please enter valid weight for ' . $name;
        //     $messages[$grade . '_total_weight.between'] = 'Please enter valid weight for ' . $name;
        //     $messages['sizering_stocks.*.' . $grade . '.numeric'] = 'Please enter valid weight for ' . $name;
        //     $messages['sizering_stocks.*.' . $grade . '.between'] = 'Please enter valid weight for ' . $name;
        // }

        return $messages;

    }
}