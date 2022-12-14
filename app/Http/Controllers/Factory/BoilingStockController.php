<?php

namespace App\Http\Controllers\Factory;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\FactoryBoiling;
use App\Models\FactoryRcnStock;
use App\Models\FactorySizering;
use App\Models\FactorySizeringBoilingMap;
use App\Models\StockyardRcnStock;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoilingStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['name' => "Boiling Stock"],
        ];
        return view('factory.boiling.index', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function listBoiling(Request $request)
    {
        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = FactoryBoiling::join('factories', 'factories.slug', '=', 'factory_boiling_stocks.factory_slug');
        $query->select('factory_boiling_stocks.slug', 'factory_name', 'boiling_number', DB::raw('DATE_FORMAT(boiling_date_time, "%d-%m-%Y") as boiling_datetime'), 'total_boiling_weight', 'balance_boiling_weight');
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('factory_name', 'like', "%" . $search . "%");
                    $q->orWhere('boiling_number', 'like', '%' . $search . '%');
                    $q->orWhere(DB::raw('DATE_FORMAT(boiling_date_time, "%d-%m-%Y %h:%i %p")'), 'like', '%' . $search . '%');
                    $q->orWhere('total_boiling_weight', 'like', '%' . $search . '%');
                    $q->orWhere('balance_boiling_weight', 'like', '%' . $search . '%');
                });
            }
        }
        if ($request->has('columns') && $request->has('order')) {
            $order_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $sort_by = $columnName_arr[$order_arr[0]['column']]['data'];
            $sort_type = $order_arr[0]['dir'];
            if ($sort_by != '' && $sort_type != '') {
                $query->orderBy($sort_by, $sort_type);
            }
        }
        $query->filterbyOffice();
        $iTotalRecords = $query->count();
        $data = $query->skip($start)->take($rowperpage)->get();

        //Add action ACL
        $data->map(function ($item) {
            return $item['action'] = [
                'view' => \Helper::userAccess('factory-boiling-view'),
                'edit' => \Helper::userAccess('factory-boiling-edit'),
                'delete' => \Helper::userAccess('factory-boiling-delete'),
            ];
        });

        return response()->json([
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalRecords,
            "aaData" => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/boiling", 'name' => "Boiling Stock"], ['name' => "Create Boiling Stock"],
        ];
        $factories = Factory::select('factory_name', 'slug')->filterByOffice()->get();
        return view('factory.boiling.create', compact('factories'), [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sizering_slugs = array_column($request->sizering_stocks, 'sizering_slug');

        $factory_sizering_stocks = FactorySizering::whereIn('slug', $sizering_slugs)->get();

        $validator_messages = $this->getValidationMessage();
        $validator_conditions = $this->getValidationRules($factory_sizering_stocks);

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        $validator->after(function ($validator) {
            foreach (request()->sizering_stocks as $index => $sizering) {
                unset($sizering['sizering_slug']);
                if (array_sum($sizering) == 0) {
                    $validator->errors()->add("sizering_stocks.$index.sizering_slug", 'Please enter atleast one input stock');
                }
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->calculateTotalWeight();
            $boiling_number_obj = FactoryBoiling::where('factory_slug', $request->factory_slug)
                ->select('boiling_number')
                ->latest('id')
                ->first();

            $boiling_number = Helper::generateAnnualResetNumber($boiling_number_obj->boiling_number ?? '', 'BNO');

            $insertboiling = [];
            $insertboiling['stockyard_rcn_stock_slug'] = $request->stockyard_rcn_stock_slug;
            $insertboiling['boiling_number'] = strtoupper($boiling_number);

            $total_boiling_weight = 0;
            if ($request->filled('factory_slug')) {
                $insertboiling['factory_slug'] = $request->factory_slug;
            }
            if ($request->filled('boiling_date_time')) {
                $insertboiling['boiling_date_time'] = $request->boiling_date_time;
            }

            foreach (Config::get('constants.grades') as $grade) {
                if ($request->filled("{$grade}_total_weight")) {
                    $total_boiling_weight +=
                    $insertboiling["{$grade}_total_weight"]
                    = $insertboiling["{$grade}_balance_weight"]
                    = $request->{"{$grade}_total_weight"};
                }
            }

            $insertboiling['total_boiling_weight'] = $total_boiling_weight;
            $insertboiling['balance_boiling_weight'] = $total_boiling_weight;
            $insertboiling['balance_boiling_rcn_stock'] = $request->total_output_weight;

            $insertData = FactoryBoiling::create($insertboiling);

            if ($insertData && $request->filled('sizering_stocks')) {
                $boiling_slug = $insertData->slug;
                $this->insertBoilingSizering($boiling_slug, $request->sizering_stocks);
            }

            return redirect()->route('factory.boiling')
                ->with('success', 'Boling stock created successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FactoryBoiling  $boiling
     * @return \Illuminate\Http\Response
     */
    public function show(FactoryBoiling $boiling)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/boiling", 'name' => "Boiling Stock"], ['name' => "Boiling Stock Details"],
        ];

        return view('factory.boiling.show', compact('boiling'), [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FactoryBoiling  $boiling
     * @return \Illuminate\Http\Response
     */
    public function edit(FactoryBoiling $boiling)
    {
        //    dd($boiling->boilingMap);
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/boiling", 'name' => "Boiling Stock"], ['name' => "Edit Boiling Stock"],
        ];

        $factories = Factory::select('factory_name', 'slug')->get();

        $factory_sizers = FactorySizering::join('factories', 'factories.slug', '=', 'factory_sizering_stocks.factory_slug')
            ->select('factory_sizering_stocks.slug', 'sizering_number')
            ->where('factories.slug', '=', $boiling->factory_slug)
            ->where('factory_stock_slug', '=', $boiling->stockyard_rcn_stock_slug)
            ->availableSizering()
            ->get();

        return view('factory.boiling.edit', compact('boiling', 'factory_sizers'), [
            'breadcrumbs' => $breadcrumbs,
        ]);
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
        $validator->after(function ($validator) {
            foreach (request()->sizering_stocks as $index => $sizering) {
                unset($sizering['sizering_slug']);
                if (array_sum($sizering) == 0) {
                    $validator->errors()->add("sizering_stocks.$index.sizering_slug", 'Please enter atleast one input stock');
                }
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $this->calculateTotalWeight();
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
            $updateboiling['balance_boiling_rcn_stock'] = $request->total_output_weight;

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
            "code" => 200,
        ]);
    }

    public function deleteSizeringMaping($boiling_sizerList)
    {
        foreach ($boiling_sizerList as $boiling_sizer) {

            $sizeringObject = FactorySizering::withTrashed()->where('slug', '=', $boiling_sizer['sizering_slug'])->first();

            foreach (Config::get('constants.grades') as $grade) {
                $sizeringObject->balance_sizering_rcn_stock += $boiling_sizer[$grade];
                $sizeringObject->{"{$grade}_balance_weight"} += $boiling_sizer[$grade];
            }

            $sizeringObject->save();
            $boiling_sizer->delete();
        }
    }

    public function insertBoilingSizering($boiling_slug, $sizering_stocks)
    {
        if (sizeof($sizering_stocks) > 0) {
            foreach ($sizering_stocks as $sizeringstock) {
                if (($total_balance = array_sum($sizeringstock)) > 0) {

                    //Add sizering stock details into sizering boiling mapping table
                    $sizeringstock['boiling_slug'] = $boiling_slug;
                    FactorySizeringBoilingMap::create($sizeringstock);

                    //Update Sizering balance weight
                    $sizeringObject = FactorySizering::withTrashed()
                        ->where('slug', '=', $sizeringstock['sizering_slug'])
                        ->first();

                    foreach ($sizeringstock as $grade => $weight) {
                        if (is_numeric($weight)) {
                            $sizeringObject->{"{$grade}_balance_weight"} -= $weight;
                        }
                    }
                    $sizeringObject->balance_sizering_rcn_stock -= $total_balance;
                    $sizeringObject->save();
                }
            }
        }
    }

    public function updateBoilingSizering($boiling, $sizering_stocks)
    {
        $already_boiling_sizering = array_column($boiling->boilingMap->toArray(), 'sizering_slug');
        $input_boiling_sizering = array_column($sizering_stocks, 'sizering_slug');
        $deleted_boiling_sizering = array_diff($already_boiling_sizering, $input_boiling_sizering);
        $new_boiling_sizering = array_diff($input_boiling_sizering, $already_boiling_sizering);
        $update_boiling_sizering = array_intersect($input_boiling_sizering, $already_boiling_sizering);

        //delete removed values
        if (sizeof($deleted_boiling_sizering) > 0) {
            $boiling_sizers = FactorySizeringBoilingMap::where('boiling_slug', '=', $boiling->slug)->whereIn('sizering_slug', $deleted_boiling_sizering);
            $boiling_sizerList = $boiling_sizers->get();
            if ($boiling_sizerList) {
                $this->deleteSizeringMaping($boiling_sizerList);
            }
        }

        if (sizeof($new_boiling_sizering) > 0 || sizeof($update_boiling_sizering) > 0) {
            foreach ($sizering_stocks as $sizeringstock) {
                $sizeringObject = FactorySizering::withTrashed()->where('slug', '=', $sizeringstock['sizering_slug'])->first();

                if (($total_balance = array_sum($sizeringstock)) > 0) {
                    //insert new values
                    if (in_array($sizeringstock['sizering_slug'], $new_boiling_sizering)) {

                        $sizeringstock['boiling_slug'] = $boiling->slug;
                        FactorySizeringBoilingMap::create($sizeringstock);

                        //Update Sizering balance weight
                        $sizeringObject = FactorySizering::withTrashed()
                            ->where('slug', '=', $sizeringstock['sizering_slug'])
                            ->first();

                        foreach ($sizeringstock as $grade => $weight) {
                            if (is_numeric($weight)) {
                                $sizeringObject->{"{$grade}_balance_weight"} -= $weight;
                            }
                        }
                        $sizeringObject->balance_sizering_rcn_stock -= $total_balance;
                        $sizeringObject->save();
                    } //update existing values
                    else if (in_array($sizeringstock['sizering_slug'], $update_boiling_sizering)) {

                        $updateObject = FactorySizeringBoilingMap::where([
                            ['sizering_slug', '=', $sizeringstock['sizering_slug']],
                            ['boiling_slug', '=', $boiling->slug],
                        ])->whereNull('deleted_at')->first();

                        foreach ($sizeringstock as $grade => $weight) {
                            if (is_numeric($weight)) {
                                $sizeringObject->{"{$grade}_balance_weight"} -= ($weight - $updateObject->{$grade});
                                $sizeringstock[$grade] = $weight;
                                $sizeringObject->balance_sizering_rcn_stock -= ($weight - $updateObject->{$grade});
                            }
                        }

                        $sizeringObject->save();
                        $updateObject->update($sizeringstock);
                    }
                }
            }
        }
    }

    /**
     * Commman Validation Rules Array for Store and Update
     *@return Array
     */
    public function getValidationRules($factory_sizering_stocks, $boiling = null): array
    {
        $validator_conditions = [
            'factory_slug' => 'required|string|exists:factories,slug,deleted_at,NULL',
            'stockyard_rcn_stock_slug' => 'required|string|exists:factory_rcn_stocks,stockyard_rcn_stock_slug,deleted_at,NULL',
            'boiling_date_time' => 'required|date|after_or_equal:today',
            'sizering_stocks' => 'required|array',
            'sizering_stocks.*.sizering_slug' => 'required|string|distinct|exists:factory_sizering_stocks,slug,deleted_at,NULL',
        ];

        foreach (Config::get('constants.grades') as $name => $grade) {
            $validator_conditions[$grade . '_total_weight'] = 'nullable|numeric|between:0,100000';
            $validator_conditions['sizering_stocks.*.' . $grade] = 'nullable|numeric|between:0,0';
        }

        $existing = [];
        if ($boiling) {
            foreach ($boiling->boilingMap as $sizeringBoiling) {
                $existing[$sizeringBoiling->sizering_slug] = $sizeringBoiling;
            }
        }

        $sizering_slugs = array_flip(array_unique(array_filter(array_column(request()->sizering_stocks, 'sizering_slug'))));

        if ($factory_sizering_stocks->count()) {
            foreach ($factory_sizering_stocks as $sizering) {
                $index = $sizering_slugs[$sizering->slug];

                foreach (Config::get('constants.grades') as $grade) {
                    if ($sizering->{"{$grade}_total_weight"}) {
                        $validator_conditions["sizering_stocks.{$index}.{$grade}"] = 'nullable|numeric|between:0,' . ($existing[$sizering->slug]->{$grade} ?? 0) + $sizering->{"{$grade}_balance_weight"};
                    }
                }
            }
        }

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
            'boiling_date_time.required' => 'Please select a valid date',
            'boiling_date_time.date' => 'Please select a valid date',
            'boiling_date_time.after_or_equal' => 'Please select a valid date',
            'sizering_stocks.*.sizering_slug.required' => 'Please select valid sizering stock',
            'sizering_stocks.*.sizering_slug.exists' => 'Please select valid sizering stock',
            'sizering_stocks.*.sizering_slug.distinct' => 'The selected sizering number already chosen',
        ];

        foreach (Config::get('constants.grades') as $name => $grade) {
            $messages[$grade . '_total_weight.numeric'] = 'Please enter valid weight for ' . $name;
            $messages[$grade . '_total_weight.between'] = 'Please enter valid weight for ' . $name;
            $messages['sizering_stocks.*.' . $grade . '.numeric'] = 'Please enter valid weight for ' . $name;
            $messages['sizering_stocks.*.' . $grade . '.between'] = 'Insufficient weight for ' . $name;
        }

        return $messages;
    }

    public function listBoilingByFactory(Request $request, $factoryslug, $stockyard_rcn_stock_slug)
    {
        $data = FactoryBoiling::join('factories', 'factories.slug', '=', 'factory_boiling_stocks.factory_slug')
            ->select('factory_boiling_stocks.slug', 'boiling_number')
            ->where('factories.slug', '=', $factoryslug)
            ->where('stockyard_rcn_stock_slug', '=', $stockyard_rcn_stock_slug)
            ->availableBoiling()
            ->get();

        return response()->json([
            "success" => true,
            "data" => $data,
        ]);
    }

    public function listBoilingByStockyard(Request $request, $factoryslug, $stockyard_rcn_stock_slug)
    {
        $data = StockyardRcnStock::where('stockyard_slug', $factoryslug)
            ->select('stockyard_slug', 'lot_number')
            ->get();

        return response()->json([
            "success" => true,
            'data' => $data,
        ]);
    }
    public function listBoilingByStockyardMixCompine(Request $request, $factoryslug, $stockyard_rcn_stock_slug)
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
    public function listStockByFactory(Request $request, $factoryslug)
    {
        $data = FactoryRcnStock::join('factories', 'factories.slug', '=', 'factory_rcn_stocks.factory_slug')
            ->join('stockyard_rcn_stocks', 'factory_rcn_stocks.stockyard_rcn_stock_slug', '=', 'stockyard_rcn_stocks.slug')
            ->select('stockyard_rcn_stocks.slug as slug', 'stockyard_rcn_stocks.lot_number')
            ->where('factories.slug', '=', $factoryslug)
            ->get();
        // dd($data);
        return response()->json([
            "success" => true,
            "data" => $data,
        ]);
    }

    public function calculateTotalWeight()
    {

        $total_output_weight = 0;
        foreach (Config::get('constants.grades') as $input) {
            $total_output_weight += request()->{"{$input}_total_weight"};
        }

        request()->merge(['total_output_weight' => $total_output_weight]);

    }
}