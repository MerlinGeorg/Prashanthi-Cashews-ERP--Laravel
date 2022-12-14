<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Office;
use App\Models\Stockyard;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Session;

class StockyardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!\Helper::userAccess('office-stockyard-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['name' => "Stockyard"],
        ];
        return view('admin.stockyard.index', [
            'breadcrumbs' => $breadcrumbs]);

    }

    public function stateAccount(Request $request, $state)
    {
        $data = Account::join('subaccounts', 'accounts.slug', '=', 'subaccounts.account_slug')->where([['account_state', '=', $state], ['subaccounts.deleted_at', '=', null]])->select('subaccounts.slug', 'accounts.account_name')->get();
        return response()->json(["account" => $data]);
    }

    public function listStockyard(Request $request)
    {
        if (!\Helper::userAccess('office-stockyard-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = Stockyard::join('offices', 'offices.slug', '=', 'stockyards.office_slug');
        $query->join('subaccounts', 'subaccounts.slug', '=', 'stockyards.sub_account_slug');
        $query->join('accounts', 'accounts.slug', '=', 'subaccounts.account_slug');
        $query->filterbyOffice();
        $query->select('stockyards.slug', 'stockyard_name', 'stockyard_reg_number', 'stockyard_short_name', 'office_name', 'account_name', 'stockyard_state');
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('stockyard_name', 'like', "%" . $search . "%");
                    $q->orWhere('stockyard_reg_number', 'like', '%' . $search . '%');
                    $q->orWhere('stockyard_short_name', 'like', '%' . $search . '%');
                    $q->orWhere('office_name', 'like', '%' . $search . '%');
                    $q->orWhere('account_name', 'like', '%' . $search . '%');
                    $q->orWhere('stockyard_state', 'like', "%" . $search . "%");
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
                'view' => \Helper::userAccess('office-stockyard-view'),
                'edit' => \Helper::userAccess('office-stockyard-edit'),
                'delete' => \Helper::userAccess('office-stockyard-delete'),
            ];
        });

        return response()->json([
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalRecords,
            "aaData" => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!\Helper::userAccess('office-stockyard-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.stockyard');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/stockyard", 'name' => "Stockyard"], ['name' => "Create Stockyard"],
        ];
        $offices = Office::select('office_name', 'slug')->filterbyOffice()->get();
        $accounts = Account::select('account_name', 'slug')->get();

        return view('admin.stockyard.create', compact('offices', 'accounts'), [
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
        if (!\Helper::userAccess('office-stockyard-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.stockyard');
        }

        $validator_messages = [
            'stockyard_name.string' => 'Please enter valid stockyard name',
            'stockyard_reg_number.string' => 'Please enter valid stockyard short name',
            'stockyard_short_name.string' => 'Please enter valid stockyard registration/door number',
            'sub_account_slug.exists' => 'The selected account is invalid',
            'office_slug.exists' => 'The selected office is invalid',
            'warehouse.*.warehouse_name.required' => 'Please enter warehouse name',
            'warehouse.*.warehouse_name.distinct' => 'Warehouse already exists',
            'warehouse.*.warehouse_account_slug.required' => 'Please select an account',
        ];
        $validator_conditions = [
            'stockyard_name' => 'required|max:32|string|unique:stockyards,stockyard_name,NULL,id,deleted_at,NULL',
            'stockyard_reg_number' => 'required|string|max:32|unique:stockyards,stockyard_reg_number,NULL,id,deleted_at,NULL',
            'stockyard_short_name' => 'required|string|max:32|unique:stockyards,stockyard_short_name,NULL,id,deleted_at,NULL',
            'contact_address_1' => 'required|string|max:128',
            'contact_address_2' => 'nullable|string|max:128',
            'stockyard_state' => 'required|string|max:32',
            'stockyard_pincode' => 'required|string|max:10',
            'sub_account_slug' => 'required|string|exists:subaccounts,slug,deleted_at,NULL',
            'office_slug' => 'required|string|exists:offices,slug,deleted_at,NULL',
            'warehouse.*.warehouse_name' => 'required|string|distinct|max:50',
            'warehouse.*.warehouse_account_slug' => 'required|string',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            // print_r($request->all());exit;
            $stockyard = Stockyard::create($request->all());

            foreach ($request->warehouse as $warehouse) {
                $warehouse['warehouse_stockyard_slug'] = $stockyard->slug;
                $warehouse['warehouse_account_state'] = $stockyard->stockyard_state;
                Warehouse::create($warehouse);
            }
            Stockyard::updateList();

            return redirect()->route('admin.stockyard')
                ->with('success', 'Office created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function show(Stockyard $stockyard)
    {
        if (!\Helper::userAccess('office-stockyard-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.stockyard');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/stockyard", 'name' => "Stockyard"], ['name' => "Stockyard Details"],
        ];
        // print_r($stockyard->subaccount->slug);exit;
        return view('admin.stockyard.show', compact('stockyard'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function edit(Stockyard $stockyard)
    {
        if (!\Helper::userAccess('office-stockyard-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.stockyard');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/stockyard", 'name' => "Stockyard"], ['name' => "Edit Stockyard"],
        ];
        $offices = Office::withTrashed()->select('office_name', 'slug')->filterbyOffice()->get();
        $accounts = Account::withTrashed()->select('account_name', 'slug')->get();
        return view('admin.stockyard.edit', compact('offices', 'accounts', 'stockyard'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stockyard $stockyard)
    {
        if (!\Helper::userAccess('office-stockyard-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.stockyard');
        }

        $validator_messages = [
            'stockyard_name.string' => 'Please enter valid stockyard name',
            'stockyard_reg_number.string' => 'Please enter valid stockyard short name',
            'stockyard_short_name.string' => 'Please enter valid stockyard registration/door number',
            'sub_account_slug.exists' => 'The selected account is invalid',
            'office_slug.exists' => 'The selected office is invalid',
            'warehouse.*.warehouse_name.required' => 'Please enter warehouse name',
            'warehouse.*.warehouse_name.distinct' => 'Warehouse already exists',
            'warehouse.*.warehouse_account_slug.required' => 'Please select an account',
        ];
        $validator_conditions = [
            'stockyard_name' => 'required|max:32|string|unique:stockyards,stockyard_name,' . $stockyard->slug . ',slug,deleted_at,NULL',
            'stockyard_reg_number' => 'required|string|max:32|unique:stockyards,stockyard_reg_number,' . $stockyard->slug . ',slug,deleted_at,NULL',
            'stockyard_short_name' => 'required|string|max:32|unique:stockyards,stockyard_short_name,' . $stockyard->slug . ',slug,deleted_at,NULL',
            'contact_address_1' => 'required|string|max:128',
            'contact_address_2' => 'nullable|string|max:128',
            'stockyard_state' => 'required|string|max:32',
            'stockyard_pincode' => 'required|string|max:10',
            'sub_account_slug' => 'required|string|exists:subaccounts,slug,deleted_at,NULL',
            'office_slug' => 'required|string|exists:offices,slug,deleted_at,NULL',
            'warehouse.*.warehouse_name' => 'required|string|distinct|max:50',
            'warehouse.*.warehouse_account_slug' => 'required|string',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $stockyard->update($request->all());

            $existing_warehouses = Warehouse::where('warehouse_stockyard_slug', $stockyard->slug)->pluck('id', 'slug');

            foreach ($request->warehouse as $warehouse) {
                $warehouse['warehouse_stockyard_slug'] = $stockyard->slug;
                $warehouse['warehouse_account_state'] = $stockyard->stockyard_state;
                $warehouse = Warehouse::updateOrCreate(['slug' => $warehouse['warehouse_slug']], $warehouse);

                unset($existing_warehouses[$warehouse->slug]);
            }

            $deleted_warehouses = Warehouse::whereIn('id', $existing_warehouses)->delete();
            Stockyard::updateList();

            return redirect()->route('admin.stockyard')
                ->with('success', 'Stockyard updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stockyard  $stockyard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stockyard $stockyard)
    {
        if (!\Helper::userAccess('office-stockyard-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.stockyard');
        }

        $stockyard->delete();
        Stockyard::updateList();

        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }

    /**
     * Stockyard RCN Stock.
     *
     */
    public function rcnStock()
    {
        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Stockyard"], ['name' => "Stock"],
        ];
        return view('admin.stockyard.rcn_stock', [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Stockyard Warehouse list.
     *
     */
    public function getWarehouses($stockyard_slug)
    {
        $warehouses = Warehouse::where('warehouse_stockyard_slug', $stockyard_slug)
            ->pluck('warehouse_name', 'slug');

        return response()->json($warehouses);

    }

}