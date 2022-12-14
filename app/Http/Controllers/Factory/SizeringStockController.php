<?php

namespace App\Http\Controllers\Factory;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\FactoryRcnStock;
use App\Models\FactorySizering;
use App\Models\StockyardRcnStock;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SizeringStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['name' => "Sizering Stock"],
        ];
        return view('factory.sizering.index', [
            'breadcrumbs' => $breadcrumbs]);

    }

    public function listSizering(Request $request)
    {
        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = FactorySizering::join('factories', 'factories.slug', '=', 'factory_sizering_stocks.factory_slug');
        // ->leftjoin('boiling_sizering_mapping', 'boiling_sizering_mapping.sizering_slug', '=', 'sizering_stocks.slug');
        $query->select('factory_sizering_stocks.slug', 'factory_name', 'sizering_number', DB::raw('DATE_FORMAT(sizering_date_time, "%d-%m-%Y") as sizering_datetime'), 'total_sizering_rcn_stock', 'balance_sizering_rcn_stock', DB::raw('(select count(*) as boilingmap from erp_factory_sizering_boiling_mapping where sizering_slug = erp_factory_sizering_stocks.slug and deleted_at is null) as boilingcount'));
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('factory_name', 'like', "%" . $search . "%");
                    $q->orWhere('sizering_number', 'like', '%' . $search . '%');
                    $q->orWhere(DB::raw('DATE_FORMAT(sizering_date_time, "%d-%m-%Y %h:%i %p")'), 'like', '%' . $search . '%');
                    $q->orWhere('total_sizering_rcn_stock', 'like', '%' . $search . '%');
                    $q->orWhere('balance_sizering_rcn_stock', 'like', '%' . $search . '%');
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
                'view' => \Helper::userAccess('factory-sizering-view'),
                'edit' => \Helper::userAccess('factory-sizering-edit'),
                'delete' => \Helper::userAccess('factory-sizering-delete'),
            ];
        });

        return response()->json([
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalRecords,
            "aaData" => $data]);
    }

    public function listSizeringByFactory(Request $request, $factoryslug, $factory_stock_slug)
    {
        $data = FactorySizering::join('factories', 'factories.slug', '=', 'factory_sizering_stocks.factory_slug')
            ->select('factory_sizering_stocks.slug', 'sizering_number')
            ->where('factories.slug', '=', $factoryslug)
            ->where('factory_stock_slug', '=', $factory_stock_slug)
            ->availableSizering()
            ->get();

        return response()->json([
            "success" => true,
            "data" => $data]);
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
            "data" => $data]);
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
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/sizering", 'name' => "Sizering Stock"], ['name' => "Create Sizering Stock"],
        ];

        $factories = Factory::select('factory_name', 'slug')->filterByOffice()->get();
        $factory_stocks = Factory::select('factory_name', 'slug')->filterByOffice()->get();

        return view('factory.sizering.create', compact('factories', 'factory_stocks'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factory_rcn_stock = FactoryRcnStock::where('stockyard_rcn_stock_slug', $request->factory_stock_slug)->first();
        $validator_messages = $this->getValidationMessage();
        $validator_conditions = $this->getValidationRules($factory_rcn_stock);

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            //Calculate Total output weight
            $this->calculateTotalWeight();

            if ($request->rcn_weight < $request->total_output_weight + $request->foreign_matter_total_weight) {
                $validator->errors()->add('total_output_weight', 'Output weight should be less than or equal to the Input RCN weight');
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $sizering_number_obj = FactorySizering::where('factory_slug', $request->factory_slug)
                ->select('sizering_number')
                ->latest('id')
                ->first();

            $sizering_number = Helper::generateAnnualResetNumber($sizering_number_obj->sizering_number ?? '', 'SNO');
            $request->merge(['sizering_number' => strtoupper($sizering_number)]);

            $balanceArray = [];

            foreach (Config::get('constants.grades') as $name => $grade) {
                if ($request->filled($grade . '_total_weight')) {
                    $balanceArray[$grade . '_balance_weight'] = $request->{"{$grade}_total_weight"};
                }
            }

            if ($request->filled('rcn_weight')) {
                $balanceArray['total_sizering_rcn_weight'] = $request->rcn_weight;
                $balanceArray['balance_sizering_rcn_weight'] = $request->rcn_weight;

            }

            if ($request->filled('rcn_bag')) {
                $balanceArray['total_sizering_rcn_bag'] = $request->rcn_bag;
                $balanceArray['balance_sizering_rcn_bag'] = $request->rcn_bag;
            }

            $balanceArray['total_sizering_rcn_stock'] = $request->total_output_weight;
            $balanceArray['balance_sizering_rcn_stock'] = $request->total_output_weight;

            if (!empty($balanceArray)) {
                $request->merge($balanceArray);
            }

            FactorySizering::create($request->all());

            //Update factory rcn stock
            $factory_rcn_stock = FactoryRcnStock::where('stockyard_rcn_stock_slug', $request->factory_stock_slug)->first();
            if ($factory_rcn_stock) {
                $factory_rcn_stock->balance_rcn_factory_stock -= $request->rcn_weight;
                $factory_rcn_stock->balance_rcn_bag -= $request->rcn_bag;
                $factory_rcn_stock->save();
            }

            return redirect()->route('factory.sizering')
                ->with('success', 'Sizering stock created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FactorySizering  $sizering
     * @return \Illuminate\Http\Response
     */
    public function show(FactorySizering $sizering)
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/sizering", 'name' => "Sizering Stock"], ['name' => "Sizering Stock Details"],
        ];
        return view('factory.sizering.show', compact('sizering'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FactorySizering  $sizering
     * @return \Illuminate\Http\Response
     */
    public function edit(FactorySizering $sizering)
    {
        $boiling_check = $sizering->boilingMap->first();
        if ($boiling_check) {
            return redirect()->route('factory.sizering')
                ->with('error', 'Sizering already used in boiling');
        } else {
            $breadcrumbs = [
                ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Stock"], ['link' => "factory/stock/sizering", 'name' => "Sizering Stock"], ['name' => "Edit Sizering Stock"],
            ];
            $factories = Factory::select('factory_name', 'slug')->get();
            $factory_stocks = Factory::select('factory_name', 'slug')->get();
            return view('factory.sizering.edit', compact('factories', 'factory_stocks', 'sizering'), [
                'breadcrumbs' => $breadcrumbs]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PackageCenter  $sizering
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FactorySizering $sizering)
    {
        $boiling_check = $sizering->boilingMap->first();
        if ($boiling_check) {
            return redirect()->route('factory.sizering')
                ->with('error', 'Sizering already used in boiling');
        }

        $factory_rcn_stock = FactoryRcnStock::where('stockyard_rcn_stock_slug', $request->factory_stock_slug)->first();
        $validator_messages = $this->getValidationMessage();
        $validator_conditions = $this->getValidationRules($factory_rcn_stock, $sizering);

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            //Calculate Total output weight
            $this->calculateTotalWeight();

            if ($request->rcn_weight < $request->total_output_weight + $request->foreign_matter_total_weight) {
                $validator->errors()->add('total_output_weight', 'Output weight should be less than or equal to the Input RCN weight');
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            //Update factory rcn stock
            $factory_rcn_stock = FactoryRcnStock::where('stockyard_rcn_stock_slug', $request->factory_stock_slug)->first();
            if ($factory_rcn_stock) {
                $factory_rcn_stock->balance_rcn_factory_stock -= ($request->rcn_weight - $sizering->rcn_weight);
                $factory_rcn_stock->balance_rcn_bag -= ($request->rcn_bag - $sizering->rcn_bag);
                $factory_rcn_stock->save();
            }

            $balanceArray = [];

            foreach (Config::get('constants.grades') as $name => $grade) {
                if ($request->filled($grade . '_total_weight')) {
                    $balanceArray[$grade . '_balance_weight'] = $request->{"{$grade}_total_weight"};
                }
            }

            if ($request->filled('rcn_weight')) {
                $balanceArray['total_sizering_rcn_weight'] = $request->rcn_weight;
                $balanceArray['balance_sizering_rcn_weight'] = $request->rcn_weight;

            }

            if ($request->filled('rcn_bag')) {
                $balanceArray['total_sizering_rcn_bag'] = $request->rcn_bag;
                $balanceArray['balance_sizering_rcn_bag'] = $request->rcn_bag;
            }

            $balanceArray['total_sizering_rcn_stock'] = $request->total_output_weight;
            $balanceArray['balance_sizering_rcn_stock'] = $request->total_output_weight;

            if (!empty($balanceArray)) {
                $request->merge($balanceArray);
            }

            $sizering->update($request->all());

            return redirect()->route('factory.sizering')
                ->with('success', 'FactorySizering Stock updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FactorySizering  $sizering
     * @return \Illuminate\Http\Response
     */
    public function destroy(FactorySizering $sizering)
    {
        $boiling_check = $sizering->boilingMap->first();
        if ($boiling_check) {
            return response()->json([
                "status" => 'Error',
                "msg" => 'Mapped to boiling',
                "code" => 200]);
        } else {
            //Update Factory RCN Stock count
            $factory_rcn_stock = FactoryRcnStock::where('stockyard_rcn_stock_slug', $sizering->factory_stock_slug)->first();

            if ($factory_rcn_stock) {
                $factory_rcn_stock->balance_rcn_factory_stock += $sizering->rcn_weight;
                $factory_rcn_stock->balance_rcn_bag += $sizering->rcn_bag;
                $factory_rcn_stock->save();
            }

            $sizering->delete();

            return response()->json([
                "status" => 'Success',
                "code" => 200]);
        }

    }

    /**
     * Commman Validation Rules Array for Store and Update
     *@return Array
     */
    public function getValidationRules($factory_rcn_stock, $sizering = null): array
    {

        $validator_conditions = [
            'factory_slug' => 'required|string|exists:factories,slug,deleted_at,NULL',
            'factory_stock_slug' => 'required|string|exists:factory_rcn_stocks,stockyard_rcn_stock_slug,deleted_at,NULL',
            'rcn_weight' => 'required|numeric|between:0,100000',
            'rcn_bag' => 'required|numeric|between:0,100000',
            'foreign_matter_total_weight' => 'nullable|numeric|between:0,100000',
        ];

        foreach (Config::get('constants.grades') as $name => $grade) {
            $validator_conditions[$grade . '_total_weight'] = 'nullable|numeric|between:0,100000';
        }

        if ($factory_rcn_stock) {
            $validator_conditions['rcn_weight'] = 'required|numeric|max:' . ($sizering->rcn_weight ?? 0) + $factory_rcn_stock->balance_rcn_factory_stock;
            $validator_conditions['rcn_bag'] = 'required|numeric|max:' . ($sizering->rcn_bag ?? 0) + $factory_rcn_stock->balance_rcn_bag;
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
            'factory_stock_slug.required' => 'Please select a factory stock',
            'factory_stock_slug.string' => 'Please enter valid factory stock',
            'rcn_weight.required' => 'Please enter valid weight',
            'rcn_weight.numeric' => 'Please enter valid weight',
            'rcn_weight.between' => 'Please enter valid weight',
            'rcn_weight.max' => 'Insufficient weight',
            'rcn_bag.required' => 'Please enter valid bags',
            'rcn_bag.numeric' => 'Please enter valid bags',
            'rcn_bag.between' => 'Please enter valid bags',
            'rcn_bag.max' => 'Insufficient bags',
            'foreign_matter_total_weight.numeric' => 'Please enter valid foreign matter weight ',
            'foreign_matter_total_weight.between' => 'Please enter valid foreign matter weight',
        ];

        foreach (Config::get('constants.grades') as $name => $grade) {
            $messages[$grade . '_total_weight.numeric'] = 'Please enter valid weight for ' . $name;
            $messages[$grade . '_total_weight.between'] = 'Please enter valid weight for ' . $name;
        }

        return $messages;
    }

    /**
     * Calculate total output weight
     */

    public function calculateTotalWeight()
    {

        $total_output_weight = 0; // request()->foreign_matter_total_weight ?? 0;
        foreach (Config::get('constants.grades') as $input) {
            $total_output_weight += request()->{"{$input}_total_weight"};
        }

        request()->merge(['total_output_weight' => $total_output_weight]);

    }
}