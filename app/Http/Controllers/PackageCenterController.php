<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Office;
use App\Models\PackageCenter;
use Illuminate\Http\Request;
use Session;

class PackageCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!\Helper::userAccess('office-package-center-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['name' => "Package Center"],
        ];
        return view('admin.package-center.index', [
            'breadcrumbs' => $breadcrumbs]);

    }

    public function listPackageCenter(Request $request)
    {
        if (!\Helper::userAccess('office-package-center-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = PackageCenter::join('offices', 'offices.slug', '=', 'packaging_centers.package_center_office_slug');
        $query->join('subaccounts', 'subaccounts.slug', '=', 'packaging_centers.package_center_sub_account_slug');
        $query->join('accounts', 'accounts.slug', '=', 'subaccounts.account_slug');
        $query->filterbyOffice();
        $query->select('packaging_centers.slug', 'package_center_name', 'package_center_short_name', 'package_center_reg_number', 'office_name', 'account_name', 'package_center_state');
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('package_center_name', 'like', "%" . $search . "%");
                    $q->orWhere('package_center_short_name', 'like', '%' . $search . '%');
                    $q->orWhere('package_center_reg_number', 'like', '%' . $search . '%');
                    $q->orWhere('office_name', 'like', '%' . $search . '%');
                    $q->orWhere('account_name', 'like', '%' . $search . '%');
                    $q->orWhere('package_center_state', 'like', "%" . $search . "%");
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
                'view' => \Helper::userAccess('office-package-center-view'),
                'edit' => \Helper::userAccess('office-package-center-edit'),
                'delete' => \Helper::userAccess('office-package-center-delete'),
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
        if (!\Helper::userAccess('office-package-center-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.package-center');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/package-center", 'name' => "Package Center"], ['name' => "Create Package Center"],
        ];
        $offices = Office::select('office_name', 'slug')->filterbyOffice()->get();
        $accounts = Account::select('account_name', 'slug')->get();
        return view('admin.package-center.create', compact('offices', 'accounts'), [
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
        if (!\Helper::userAccess('office-package-center-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.package-center');
        }

        $validator_messages = [
            'package_center_name.string' => 'Please enter valid stockyard name',
            'package_center_reg_number.string' => 'Please enter valid stockyard short name',
            'package_center_short_name.string' => 'Please enter valid stockyard registration/door number',
            'package_center_sub_account_slug.exists' => 'The selected account is invalid',
            'package_center_office_slug.exists' => 'The selected office is invalid',
        ];
        $validator_conditions = [
            'package_center_name' => 'required|max:32|string|unique:packaging_centers,package_center_name,NULL,id,deleted_at,NULL',
            'package_center_short_name' => 'required|string|max:32|unique:packaging_centers,package_center_short_name,NULL,id,deleted_at,NULL',
            'package_center_reg_number' => 'required|string|max:32|unique:packaging_centers,package_center_reg_number,NULL,id,deleted_at,NULL',
            'package_center_contact_address_1' => 'required|string|max:128',
            'package_center_contact_address_2' => 'nullable|string|max:128',
            'package_center_power_allocation' => 'required|string|max:32',
            'package_center_location' => 'required|string|max:32',
            'package_center_state' => 'required|string|max:32',
            'package_center_pincode' => 'required|string|max:10',
            'package_center_sub_account_slug' => 'required|string|exists:subaccounts,slug,deleted_at,NULL',
            'package_center_office_slug' => 'required|string|exists:offices,slug,deleted_at,NULL',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            // print_r($request->all());exit;
            PackageCenter::create($request->all());
            PackageCenter::updateList();

            return redirect()->route('admin.package-center')
                ->with('success', 'Package Center created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PackageCenter  $package_center
     * @return \Illuminate\Http\Response
     */
    public function show(PackageCenter $package_center)
    {
        if (!\Helper::userAccess('office-package-center-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.package-center');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/package-center", 'name' => "Package Center"], ['name' => "Package Center Details"],
        ];
        // print_r($stockyard->subaccount->slug);exit;
        return view('admin.package-center.show', compact('package_center'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PackageCenter  $package_center
     * @return \Illuminate\Http\Response
     */
    public function edit(PackageCenter $package_center)
    {
        if (!\Helper::userAccess('office-package-center-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.package-center');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/package-center", 'name' => "Package Center"], ['name' => "Edit Package Center"],
        ];
        $offices = Office::withTrashed()->select('office_name', 'slug')->filterbyOffice()->get();
        $accounts = Account::withTrashed()->select('account_name', 'slug')->get();
        return view('admin.package-center.edit', compact('offices', 'accounts', 'package_center'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PackageCenter  $package_center
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PackageCenter $package_center)
    {
        if (!\Helper::userAccess('office-package-center-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.package-center');
        }

        $validator_messages = [
            'package_center_name.string' => 'Please enter valid stockyard name',
            'package_center_reg_number.string' => 'Please enter valid stockyard short name',
            'package_center_short_name.string' => 'Please enter valid stockyard registration/door number',
            'package_center_sub_account_slug.exists' => 'The selected account is invalid',
            'package_center_office_slug.exists' => 'The selected office is invalid',
        ];
        $validator_conditions = [
            'package_center_name' => 'required|max:32|string|unique:packaging_centers,package_center_name,' . $package_center->slug . ',slug,deleted_at,NULL',
            'package_center_short_name' => 'required|string|max:32|unique:packaging_centers,package_center_short_name,' . $package_center->slug . ',slug,deleted_at,NULL',
            'package_center_reg_number' => 'required|string|max:32|unique:packaging_centers,package_center_reg_number,' . $package_center->slug . ',slug,deleted_at,NULL',
            'package_center_contact_address_1' => 'required|string|max:128',
            'package_center_contact_address_2' => 'nullable|string|max:128',
            'package_center_power_allocation' => 'required|string|max:32',
            'package_center_location' => 'required|string|max:32',
            'package_center_state' => 'required|string|max:32',
            'package_center_pincode' => 'required|string|max:10',
            'package_center_sub_account_slug' => 'required|string|exists:subaccounts,slug,deleted_at,NULL',
            'package_center_office_slug' => 'required|string|exists:offices,slug,deleted_at,NULL',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $package_center->update($request->all());
            PackageCenter::updateList();

            return redirect()->route('admin.package-center')
                ->with('success', 'Package Center updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PackageCenter  $package_center
     * @return \Illuminate\Http\Response
     */
    public function destroy(PackageCenter $package_center)
    {
        if (!\Helper::userAccess('office-package-center-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.package-center');
        }

        $package_center->delete();
        PackageCenter::updateList();

        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }
}