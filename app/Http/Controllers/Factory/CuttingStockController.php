<?php

namespace App\Http\Controllers\Factory;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\FactoryBoiling;
use App\Models\FactoryCutting;
use App\Models\FactoryCuttingBoilingMap;
use App\Models\FactoryRcnStock;
use Config;use Illuminate\Http\Request;use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\DB;

class CuttingStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['name' => "Cutting Stock"],
        ];
        return view('factory.cutting.index', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function listCutting(Request $request)
    {
        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = FactoryCutting::join('factories', 'factories.slug', '=', 'factory_cutting_stocks.factory_slug');
        $query->select('factory_cutting_stocks.slug', 'factory_name', 'cutting_work_number', DB::raw('DATE_FORMAT(cutting_date_time, "%d-%m-%Y") as cutting_datetime'), 'cutting_type');
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('factory_name', 'like', "%" . $search . "%");
                    $q->orWhere('cutting_work_number', 'like', '%' . $search . '%');
                    $q->orWhere(DB::raw('DATE_FORMAT(cutting_date_time, "%d-%m-%Y %h:%i %p")'), 'like', '%' . $search . '%');
                    $q->orWhere('cutting_type', 'like', '%' . $search . '%');
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
        $iTotalRecords = $query->count();
        $data = $query->skip($start)->take($rowperpage)->get();

        //Add action ACL
        $data->map(function ($item) {
            return $item['action'] = [
                'view' => \Helper::userAccess('factory-cutting-view'),
                'edit' => \Helper::userAccess('factory-cutting-edit'),
                'delete' => \Helper::userAccess('factory-cutting-delete'),
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
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/cutting", 'name' => "Cutting Stock"], ['name' => "Create Cutting Stock"],
        ];
        $factories = Factory::select('factory_name', 'slug')->filterByOffice()->get();
        return view('factory.cutting.create', compact('factories'), [
            'breadcrumbs' => $breadcrumbs,
        ]);
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
            'stockyard_rcn_stock_slug.string' => 'Please enter valid lot number',
            'cutting_type.required' => 'Please select a cutting type',
            'cutting_date_time.required' => 'Please select a valid date',
            'cutting_date_time.date' => 'Please select a valid date',
            'cutting_date_time.after_or_equal' => 'Please select a valid date',
            'boiling_stocks.*.boiling_slug.required' => 'Please select valid boiling stock',
            'boiling_stocks.*.boiling_slug.exists' => 'Please select valid boiling stock',
            'boiling_stocks.*.boiling_slug.distinct' => 'The selected boiling number is already chosen',
            'boiling_stocks.*.boiling_slug.required_if' => 'Boiling stock required if machinery',
            'wholes.numeric' => 'Please enter valid weight',
            'wholes.between' => 'Please enter valid weight',
            'brokens.numeric' => 'Please enter valid weight',
            'brokens.between' => 'Please enter valid weight',
            'piruwel.numeric' => 'Please enter valid weight',
            'piruwel.between' => 'Please enter valid weight',
            'rejection.numeric' => 'Please enter valid weight',
            'rejection.between' => 'Please enter valid weight',
            'uncut.numeric' => 'Please enter valid weight',
            'uncut.between' => 'Please enter valid weight',
            'unscoop.numeric' => 'Please enter valid weight',
            'unscoop.between' => 'Please enter valid weight',
            'given_rcn_bag.required_if' => 'Given RCN bag is required',
            'given_rcn_weight.required_if' => 'Given RCN weight is required',
        ];
        if (request()->cutting_type == 'machinery') {
            foreach (Config::get('constants.grades') as $name => $grade) {
                $messages[$grade . '_total_weight.numeric'] = 'Please enter valid weight for ' . $name;
                $messages[$grade . '_total_weight.between'] = 'Please enter valid weight for ' . $name;
                $messages['boiling_stocks.*.' . $grade . '.numeric'] = 'Please enter valid weight for ' . $name;
                $messages['boiling_stocks.*.' . $grade . '.between'] = 'Insufficient weight for ' . $name;
            }
        }
        return $messages;
    }

    public function getValidationRules($factory_boiling_stocks, $cutting = null): array
    {

        $validator_conditions = [
            'factory_slug' => 'required|string|exists:factories,slug,deleted_at,NULL',
            'stockyard_rcn_stock_slug' => 'required|string|exists:factory_rcn_stocks,stockyard_rcn_stock_slug,deleted_at,NULL',
            'cutting_date_time' => 'required|date|after_or_equal:today',
            'cutting_type' => 'required|string',
            'given_rcn_bag' => 'nullable|required_if:cutting_type,traditional|integer|between:0,100000',
            'given_rcn_weight' => 'nullable|required_if:cutting_type,traditional|numeric|between:0,100000',
            'boiling_stocks' => 'required_if:cutting_type,machinery|array',
            'boiling_stocks.*.boiling_slug' => 'required_if:cutting_type,machinery|string|distinct|exists:factory_boiling_stocks,slug,deleted_at,NULL',
            'wholes' => 'nullable|numeric|between:0,100000',
            'brokens' => 'nullable|numeric|between:0,100000',
            'piruwel' => 'nullable|numeric|between:0,100000',
            'rejection' => 'nullable|numeric|between:0,100000',
            'uncut' => 'nullable|numeric|between:0,100000',
            'unscoop' => 'nullable|numeric|between:0,100000',
        ];

        if (request()->cutting_type == 'machinery') {

            foreach (Config::get('constants.grades') as $name => $grade) {
                $validator_conditions[$grade . '_total_weight'] = 'nullable|numeric|between:0,100000';
                $validator_conditions['boiling_stocks.*.' . $grade] = 'nullable|numeric|between:0,0';
            }

            $existing = [];
            if ($cutting) {

                foreach ($cutting->cuttingMap as $boilingCutting) {
                    $existing[$boilingCutting->boiling_slug] = $boilingCutting;
                }
            }

            $boiling_slugs = array_flip(array_unique(array_filter(array_column(request()->boiling_stocks, 'boiling_slug'))));

            if ($factory_boiling_stocks->count()) {
                foreach ($factory_boiling_stocks as $boiling) {
                    $index = $boiling_slugs[$boiling->slug];

                    foreach (Config::get('constants.grades') as $grade) {

                        if ($boiling->{"{$grade}_total_weight"}) {
                            $validator_conditions["boiling_stocks.{$index}.{$grade}"] = 'nullable|numeric|between:0,' . ($existing[$boiling->slug]->{$grade} ?? 0) + $boiling->{"{$grade}_balance_weight"};
                        }
                    }

                }
            }

        }
        return $validator_conditions;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator_messages = $this->getValidationMessage();

        $boiling_slugs = array_column($request->boiling_stocks, 'boiling_slug');
        $factory_boiling_stocks = FactoryBoiling::whereIn('slug', $boiling_slugs)->get();

        $validator_conditions = $this->getValidationRules($factory_boiling_stocks);

        if ($request->filled('cutting_type') && $request->cutting_type == 'traditional') {
            $requestdata = $request->except(['boiling_stocks']);
        } else {
            $requestdata = $request->all();
        }

        $validator = \Validator::make($requestdata, $validator_conditions, $validator_messages);
        $validator->after(function ($validator) {

            if (request()->boiling_stocks && request()->cutting_type == "machinery") {
                foreach (request()->boiling_stocks as $index => $boiling) {
                    unset($boiling['boiling_slug']);
                    if (array_sum($boiling) == 0) {
                        $validator->errors()->add("boiling_stocks.$index.boiling_slug", 'Please enter atleast one input stock');
                    }
                }
            }
        });

        if ($validator->fails()) {

            return back()
                ->withErrors($validator)
                ->withInput();
        } else {

            $this->calculateTotalWeight();
            $cutting_number_obj = FactoryCutting::where('factory_slug', $request->factory_slug)
                ->select('cutting_work_number')
                ->latest('id')
                ->first();
            $factory_obj = Factory::where('slug', $request->factory_slug)
                ->select('factory_short_name')
                ->latest('id')
                ->first();
            $lot_num = '';
            $prefix = '';
            if ($factory_obj) {
                $prefix = $factory_obj->factory_short_name;
            }
            if ($cutting_number_obj) {
                $lot_num = $cutting_number_obj->cutting_work_number;
            }
            $cutting_number = Helper::generateAnnualResetNumber($lot_num, $prefix);
            $insertcutting = [];
            if ($cutting_number) {
                $insertcutting['cutting_work_number'] = $cutting_number;
            }
            $total_cutting_weight = 0;
            if ($request->filled('factory_slug')) {
                $insertcutting['factory_slug'] = $request->factory_slug;
            }
            if ($request->filled('stockyard_rcn_stock_slug')) {
                $insertcutting['stockyard_rcn_stock_slug'] = $request->stockyard_rcn_stock_slug;
            }
            if ($request->filled('cutting_date_time')) {
                $insertcutting['cutting_date_time'] = $request->cutting_date_time;
            }
            if ($request->filled('cutting_type')) {
                $insertcutting['cutting_type'] = $request->cutting_type;
            }
            if ($request->filled('given_rcn_bag')) {
                $insertcutting['given_rcn_bag'] = $request->given_rcn_bag;
            }
            if ($request->filled('given_rcn_weight')) {
                $insertcutting['given_rcn_weight'] = $request->given_rcn_weight;
                $total_cutting_weight += $insertcutting['given_rcn_weight'];
            }
            if ($request->filled('wholes')) {
                $insertcutting['wholes'] = $request->wholes;
                $insertcutting['balance_wholes'] = $request->balance_wholes;
            }
            if ($request->filled('brokens')) {
                $insertcutting['brokens'] = $request->brokens;
                $insertcutting['balance_brokens'] = $request->balance_brokens;
            }
            if ($request->filled('piruwel')) {
                $insertcutting['piruwel'] = $request->piruwel;
                $insertcutting['balance_piruwel'] = $request->balance_piruwel;
            }
            if ($request->filled('rejection')) {
                $insertcutting['rejection'] = $request->rejection;
            }
            if ($request->filled('uncut')) {
                $insertcutting['uncut'] = $request->uncut;
            }
            if ($request->filled('unscoop')) {
                $insertcutting['unscoop'] = $request->unscoop;
            }

            foreach (Config::get('constants.grades') as $grade) {
                if ($request->filled("{$grade}_total_weight")) {
                    $total_cutting_weight +=
                    $insertcutting["{$grade}_total_weight"]
                    = $insertcutting["{$grade}_balance_weight"]
                    = $request->{"{$grade}_total_weight"};
                }
            }

            $insertcutting['total_cutting_weight'] = $total_cutting_weight;
            $insertcutting['balance_cutting_weight'] = $total_cutting_weight;
            $insertcutting['balance_cutting_rcn_stock'] = $request->total_output_weight;

            $insertData = FactoryCutting::create($insertcutting);
            if ($insertData && $request->filled('boiling_stocks') && $request->cutting_type == 'machinery') {

                $cutting_slug = $insertData->slug;
                $this->insertCuttingBoiling($cutting_slug, $request->boiling_stocks);
            }
            // if ($request->cutting_type == 'traditional') {

            //     if ($request->boiling_stocks) {
            //         foreach ($request->boiling_stocks as $boiling_stocks) {
            //             $boilingObject = FactoryBoiling::where('slug', '=', $boiling_stocks['boiling_slug'])->first();

            //             $boilingObject->balance_boiling_rcn_stock -= $insertcutting['given_rcn_weight'];
            //             $boilingObject->balance_boiling_weight -= $insertcutting['given_rcn_weight'];
            //             $boilingObject->save();
            //         }
            //     }
            // }
            return redirect()->route('factory.cutting')
                ->with('success', 'Cutting stock created successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cutting  $cutting
     * @return \Illuminate\Http\Response
     */
    public function show(FactoryCutting $cutting)
    {

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/cutting", 'name' => "Cutting Stock"], ['name' => "Edit Cutting Stock"],
        ];
        $factories = Factory::select('factory_name', 'slug')->get();
        //  $factory_sizers = FactorySizering::join('factories', 'factories.slug', '=', 'factory_sizering_stocks.factory_slug')->select('factory_sizering_stocks.slug', 'sizering_number')->where('factories.slug', '=', $cutting->factory_slug)->get();
        $factory_boilers = FactoryBoiling::join('factories', 'factories.slug', '=', 'factory_boiling_stocks.factory_slug')->select('factory_boiling_stocks.slug', 'boiling_number')->where('factories.slug', '=', $cutting->factory_slug)->get();
        // print_r(sizeof($boiling->boilingMap));exit;
        return view('factory.cutting.show', compact('cutting', 'factory_boilers'), [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cutting  $cutting
     * @return \Illuminate\Http\Response
     */
    public function edit(FactoryCutting $cutting)
    {

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/cutting", 'name' => "Cutting Stock"], ['name' => "Edit Cutting Stock"],
        ];
        $factories = Factory::select('factory_name', 'slug')->get();
        //  $factory_sizers = FactorySizering::join('factories', 'factories.slug', '=', 'factory_sizering_stocks.factory_slug')->select('factory_sizering_stocks.slug', 'sizering_number')->where('factories.slug', '=', $cutting->factory_slug)->get();
        $factory_boilers = FactoryBoiling::join('factories', 'factories.slug', '=', 'factory_boiling_stocks.factory_slug')->select('factory_boiling_stocks.slug', 'boiling_number')->where('factories.slug', '=', $cutting->factory_slug)->get();
        // print_r(sizeof($boiling->boilingMap));exit;
        return view('factory.cutting.edit', compact('factories', 'cutting', 'factory_boilers'), [
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
    public function update(Request $request, FactoryCutting $cutting)
    {
        $validator_messages = $this->getValidationMessage();
        $factory_boiling_stocks = null;
        if ($request->has('boiling_stocks')) {
            $boiling_slugs = array_column($request->boiling_stocks, 'boiling_slug');
            $factory_boiling_stocks = FactoryBoiling::whereIn('slug', $boiling_slugs)->get();
        }

        $validator_conditions = $this->getValidationRules($factory_boiling_stocks, $cutting);

        if ($request->filled('cutting_type') && $request->cutting_type == 'traditional') {
            $requestdata = $request->except(['boiling_stocks']);
        } else {
            $requestdata = $request->all();
        }

        $validator = \Validator::make($requestdata, $validator_conditions, $validator_messages);
        $validator->after(function ($validator) {
            if (request()->boiling_stocks) {
                foreach (request()->boiling_stocks as $index => $boiling) {
                    unset($boiling['boiling_slug']);
                    if (array_sum($boiling) == 0) {
                        $validator->errors()->add("boiling_stocks.$index.boiling_slug", 'Please enter atleast one input stock');
                    }
                }
            }
        });

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();

        } else {
            $this->calculateTotalWeight();
            $updatecutting = [];
            $total_cutting_weight = 0;
            foreach (Config::get('constants.grades') as $grade) {
                if ($request->filled("{$grade}_total_weight")) {
                    $total_cutting_weight +=
                    $updatecutting["{$grade}_total_weight"]
                    = $updatecutting["{$grade}_balance_weight"]
                    = $request->{"{$grade}_total_weight"};
                }
            }

            if ($request->filled('given_rcn_bag')) {
                $updatecutting['given_rcn_bag'] = $request->given_rcn_bag;
            }
            if ($request->filled('given_rcn_weight')) {
                $updatecutting['given_rcn_weight'] = $request->given_rcn_weight;
                $total_cutting_weight += $updatecutting['given_rcn_weight'];
            }

            $updatecutting['total_cutting_weight'] = $total_cutting_weight;
            $updatecutting['balance_cutting_weight'] = $total_cutting_weight;
            $updatecutting['cutting_date_time'] = $request->cutting_date_time;
            $updateboiling['balance_cutting_rcn_stock'] = $request->total_output_weight;

            if ($request->filled('wholes')) {
                $updatecutting['wholes'] = $request->wholes;
                $updatecutting['balance_wholes'] = $request->balance_wholes;
            }
            if ($request->filled('brokens')) {
                $updatecutting['brokens'] = $request->brokens;
                $updatecutting['balance_brokens'] = $request->balance_brokens;
            }
            if ($request->filled('piruwel')) {
                $updatecutting['piruwel'] = $request->piruwel;
                $updatecutting['balance_piruwel'] = $request->balance_piruwel;
            }
            if ($request->filled('rejection')) {
                $updatecutting['rejection'] = $request->rejection;
            }
            if ($request->filled('uncut')) {
                $updatecutting['uncut'] = $request->uncut;
            }
            if ($request->filled('unscoop')) {
                $updatecutting['unscoop'] = $request->unscoop;
            }

            $cutting->update($updatecutting);
            if ($request->filled('boiling_stocks')) {
                $this->updateCuttingBoiling($cutting, $request->boiling_stocks);
            }
            // if ($request->cutting_type == 'traditional') {
            //     if ($request->boiling_stocks) {
            //         $boilingObject = FactoryBoiling::withTrashed()->where('slug', '=', $request->boiling_stocks['boiling_slug'])->first();
            //         $boilingObject->balance_boiling_rcn_stock -= $updatecutting['given_rcn_weight'];
            //         $boilingObject->balance_boiling_weight -= $updatecutting['given_rcn_weight'];
            //         $boilingObject->save();
            //     }
            // }

            return redirect()->route('factory.cutting')
                ->with('success', 'Cutting Stock updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cutting  $cutting
     * @return \Illuminate\Http\Response
     */
    public function destroy(FactoryCutting $cutting)
    {

        $cutting_boilers = FactoryCuttingBoilingMap::where('cutting_slug', '=', $cutting->slug);
        $cutting_boilingList = $cutting_boilers->get();

        if ($cutting_boilingList) {
            $this->deleteBoilingMaping($cutting_boilingList);
        }
        $cutting->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200,
        ]);
    }

    public function deleteBoilingMaping($cutting_boiling_list)
    {

        foreach ($cutting_boiling_list as $cutting_boiling) {

            $boilingObject = FactoryBoiling::withTrashed()->where('slug', '=', $cutting_boiling['boiling_slug'])->first();

            foreach (Config::get('constants.grades') as $grade) {
                $boilingObject->balance_boiling_rcn_stock += $cutting_boiling[$grade];
                $boilingObject->balance_boiling_weight += $cutting_boiling[$grade];
                $boilingObject->{"{$grade}_balance_weight"} += $cutting_boiling[$grade];
            }

            $boilingObject->save();
            $cutting_boiling->delete();
        }
    }

    public function insertCuttingBoiling($cutting_slug, $boiling_stocks)
    {
        if (sizeof($boiling_stocks) > 0) {
            foreach ($boiling_stocks as $boilingstock) {
                if (($total_balance = array_sum($boilingstock)) > 0) {

                    $boilingstock['cutting_slug'] = $cutting_slug;
                    FactoryCuttingBoilingMap::create($boilingstock);

                    $boilingObject = FactoryBoiling::withTrashed()->where('slug', '=', $boilingstock['boiling_slug'])->first();

                    foreach ($boilingstock as $grade => $weight) {
                        if (is_numeric($weight)) {
                            $boilingObject->{"{$grade}_balance_weight"} -= $weight;
                        }
                    }
                    $boilingObject->balance_boiling_rcn_stock -= $total_balance;
                    $boilingObject->balance_boiling_weight -= $total_balance;
                    $boilingObject->save();
                }
            }
        }
        return true;
    }

    public function updateCuttingBoiling($cutting, $boilingstocks)
    {
        // $already_boiling_sizering = $boiling->boilingMap->toArray();
        $already_cutting_boiling = array_column($cutting->cuttingMap->toArray(), 'boiling_slug');
        $input_cutting_boiling = array_column($boilingstocks, 'boiling_slug');
        $deleted_cutting_boiling = array_diff($already_cutting_boiling, $input_cutting_boiling);
        $new_cutting_boiling = array_diff($input_cutting_boiling, $already_cutting_boiling);
        $update_boiling_sizering = array_intersect($input_cutting_boiling, $already_cutting_boiling);
        //delete removed values
        if (sizeof($deleted_cutting_boiling) > 0) {
            $boiling_sizers = FactoryCuttingBoilingMap::where('cutting_slug', '=', $cutting->slug)->whereIn('boiling_slug', $deleted_cutting_boiling);
            $boiling_sizerList = $boiling_sizers->get();
            if ($boiling_sizerList) {
                $this->deleteBoilingMaping($boiling_sizerList);
            }
        }
        if (sizeof($new_cutting_boiling) > 0 || sizeof($update_boiling_sizering) > 0) {
            foreach ($boilingstocks as $boilingstock) {
                $boilingObject = FactoryBoiling::withTrashed()->where('slug', '=', $boilingstock['boiling_slug'])->first();
                if (($total_balance = array_sum($boilingstock)) > 0) {
                    //insert new values
                    if (in_array($boilingstock['boiling_slug'], $new_cutting_boiling)) {
                        $boilingstock['cutting_slug'] = $cutting->slug;
                        FactoryCuttingBoilingMap::create($boilingstock);

                        foreach ($boilingstock as $grade => $weight) {
                            if (is_numeric($weight)) {
                                $boilingstock->{"{$grade}_balance_weight"} -= $weight;
                            }
                        }
                        $boilingObject->balance_boiling_rcn_stock -= $total_balance;
                        $boilingObject->balance_boiling_weight -= $total_balance;

                        $boilingObject->save();
                    } //update existing values
                    else if (in_array($boilingstock['boiling_slug'], $update_boiling_sizering)) {
                        $updateObject = FactoryCuttingBoilingMap::where([
                            ['boiling_slug', '=', $boilingstock['boiling_slug']],
                            ['cutting_slug', '=', $cutting->slug],
                        ])->whereNull('deleted_at')->first();
                        foreach ($boilingstock as $grade => $weight) {
                            if (is_numeric($weight)) {
                                $boilingObject->{"{$grade}_balance_weight"} -= ($weight - $updateObject->{$grade});
                                $sizeringstock[$grade] = $weight;
                                $boilingObject->balance_boiling_rcn_stock -= ($weight - $updateObject->{$grade});
                                $boilingObject->balance_boiling_weight -= ($weight - $updateObject->{$grade});
                            }
                        }
                        $updateObject->update($boilingstock);
                        $boilingObject->save();
                    }
                }
            }
        }
        return true;
    }

    public function listStockByFactory(Request $request, $factoryslug)
    {
        $data = FactoryRcnStock::join('factories', 'factories.slug', '=', 'factory_rcn_stocks.factory_slug')
            ->join('stockyard_rcn_stocks', 'factory_rcn_stocks.stockyard_rcn_stock_slug', '=', 'stockyard_rcn_stocks.slug')
            ->select('stockyard_rcn_stocks.slug as slug', 'stockyard_rcn_stocks.lot_number')
            ->where('factories.slug', '=', $factoryslug)
            ->get();

        return response()->json([
            "success" => true,
            "data" => $data,
        ]);
    }

    public function calculateTotalWeight()
    {

        $total_output_weight = 0;

        $total_output_weight += (request()->wholes + request()->brokens + request()->piruwel + request()->rejection + request()->uncut + request()->unscoop);
        request()->merge(['total_output_weight' => $total_output_weight]);

    }
}