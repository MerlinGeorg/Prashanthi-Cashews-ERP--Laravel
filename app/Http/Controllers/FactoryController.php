<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Factory;
use App\Models\FactoryProcessing;
use App\Models\Office;
use Config;
use Illuminate\Http\Request;
use Session;

class FactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!\Helper::userAccess('office-factory-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['name' => "Factory"],
        ];
        return view('admin.factory.index', [
            'breadcrumbs' => $breadcrumbs]);

    }

    public function listFactory(Request $request)
    {
        if (!\Helper::userAccess('office-factory-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = Factory::join('offices', 'offices.slug', '=', 'factories.factory_office_slug');
        $query->join('subaccounts', 'subaccounts.slug', '=', 'factories.factory_sub_account_slug');
        $query->join('accounts', 'accounts.slug', '=', 'subaccounts.account_slug');
        $query->filterbyOffice();
        $query->select('factory_of', 'factory_sub_account_slug', 'factory_office_slug', 'factory_name', 'factory_short_name', 'factory_reg_number', 'factory_location', 'factory_power_allocation', 'factory_contact_address_1', 'factory_contact_address_2', 'factory_state', 'factory_pincode', 'factories.slug', 'factory_name', 'office_name', 'account_name');
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('factory_name', 'like', "%" . $search . "%");
                    $q->orWhere('factory_reg_number', 'like', '%' . $search . '%');
                    $q->orWhere('factory_short_name', 'like', '%' . $search . '%');
                    $q->orWhere('office_name', 'like', '%' . $search . '%');
                    $q->orWhere('account_name', 'like', '%' . $search . '%');
                    $q->orWhere('factory_state', 'like', "%" . $search . "%");
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
                'view' => \Helper::userAccess('office-factory-view'),
                'edit' => \Helper::userAccess('office-factory-edit'),
                'delete' => \Helper::userAccess('office-factory-delete'),
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
        if (!\Helper::userAccess('office-factory-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.factory');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/factory", 'name' => "Factory"], ['name' => "Create Factory"],
        ];
        $offices = Office::select('office_name', 'slug')->filterbyOffice()->get();
        $accounts = Account::select('account_name', 'slug')->get();
        return view('admin.factory.create', compact('offices', 'accounts'), [
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
        if (!\Helper::userAccess('office-factory-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.factory');
        }

        $validator_messages = [
            'factory_of.required' => 'Factory of required',
            'factory_of.in' => 'Please select a valid Factory of',
            'factory_name.required' => 'Factory name required',
            'factory_short_name.required' => 'Factory short name required',
            'factory_reg_number.required' => 'Factory registration/door number required',
            'factory_location.required' => 'Factory location required',
            'factory_office_slug.required' => 'Factory office required',
            'factory_state.required' => 'Factory state required',
            'factory_state.in' => 'Please select a valid state',
            'factory_sub_account_slug.required' => 'Account required',
            'factory_power_allocation.required' => 'Power allocation required',
            'factory_contact_address_1.required' => 'Address1 required',
            'factory_contact_address_2.required' => 'Address2 required',
            'factory_pincode.required' => 'Pincode required',
            'processor_types_list.array' => 'Please enter valid processor types',
            'processor_types_list.*.processing_type.required' => 'Please select processor type list',
            'processor_types_list.*.processing_type.distinct' => 'Processor type list already selected',
            'processor_types_list.*.processing_type.max' => 'Please select a valid Processor type',
            'processor_types_list.*.processing_capacity.required' => 'Processor capacity required',
            'processor_types_list.*.processing_capacity.required' => 'Please enter valid processor capacity',
        ];
        $validator_conditions = [
            'factory_of' => 'required|string|in:' . implode(',', config('constants.factory_of')),
            'factory_name' => 'required|max:32|string|unique:factories,factory_name,NULL,slug,deleted_at,NULL',
            'factory_short_name' => 'required|max:32|unique:factories,factory_short_name,NULL,slug,deleted_at,NULL',
            'factory_reg_number' => 'required|max:32|unique:factories,factory_reg_number,NULL,slug,deleted_at,NULL',
            'factory_location' => 'required|max:32|string',
            'factory_office_slug' => 'required|string|exists:offices,slug,deleted_at,NULL',
            'factory_state' => 'required|max:32|in:' . implode(',', config('constants.states')),
            'factory_sub_account_slug' => 'required|string|exists:subaccounts,slug,deleted_at,NULL',
            'factory_power_allocation' => 'required|string|max:32',
            'factory_contact_address_1' => 'required|string|max:128',
            'factory_contact_address_2' => 'nullable|string|max:128',
            'factory_pincode' => 'required|max:10',
            'processor_types_list' => 'exclude_unless:factory_of,Prashanthi|array',
            'processor_types_list.*.processing_type' => 'required|distinct|max:64',
            'processor_types_list.*.processing_capacity' => 'required|max:24',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $factory = Factory::create($request->except('processor_types_list'));
            if ($request->factory_of == 'Prashanthi') {
                if (sizeof($request->processor_types_list ?? []) > 0) {
                    foreach ($request->processor_types_list as $processor_types) {
                        $processor_type = ['factory_slug' => $factory->slug, 'factory_processing_types' => $processor_types['processing_type'], 'factory_processing_capacity' => $processor_types['processing_capacity']];
                        FactoryProcessing::create($processor_type);
                    }
                }
            }
            Factory::updateList();

            return redirect()->route('admin.factory')
                ->with('success', 'Factory created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function show(Factory $factory)
    {
        if (!\Helper::userAccess('office-factory-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.factory');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/factory", 'name' => "Factory"], ['name' => "Factory Details"],
        ];
        // print_r($stockyard->subaccount->slug);exit;
        return view('admin.factory.show', compact('factory'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function edit(Factory $factory)
    {
        if (!\Helper::userAccess('office-factory-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.factory');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/factory", 'name' => "Factory"], ['name' => "Edit Factory"],
        ];
        $offices = Office::withTrashed()->select('office_name', 'slug')->filterbyOffice()->get();
        $accounts = Account::withTrashed()->select('account_name', 'slug')->get();
        return view('admin.factory.edit', compact('offices', 'accounts', 'factory'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factory $factory)
    {
        if (!\Helper::userAccess('office-factory-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.factory');
        }

        // print_r($request->all());exit;
        $validator_messages = [
            'factory_of.required' => 'Factory of required',
            'factory_of.in' => 'Please select a valid Factory of',
            'factory_name.required' => 'Factory name required',
            'factory_short_name.required' => 'Factory short name required',
            'factory_reg_number.required' => 'Factory registration/door number required',
            'factory_location.required' => 'Factory location required',
            'factory_office_slug.required' => 'Factory office required',
            'factory_state.required' => 'Factory state required',
            'factory_state.in' => 'Please select a valid state',
            'factory_sub_account_slug.required' => 'Account required',
            'factory_power_allocation.required' => 'Power allocation required',
            'factory_contact_address_1.required' => 'Address1 required',
            'factory_contact_address_2.required' => 'Address2 required',
            'factory_pincode.required' => 'Pincode required',
            'processor_types_list.array' => 'Please enter valid processor types',
            'processor_types_list.*.processing_type.required' => 'Please select processor type list',
            'processor_types_list.*.processing_type.distinct' => 'Processor type list already selected',
            'processor_types_list.*.processing_type.max' => 'Please select a valid Processor type',
            'processor_types_list.*.processing_capacity.required' => 'Processor capacity required',
            'processor_types_list.*.processing_capacity.required' => 'Please enter valid processor capacity',
        ];
        $validator_conditions = [
            'factory_of' => 'required|string|in:' . implode(',', config('constants.factory_of')),
            'factory_name' => 'required|max:32|string|unique:factories,factory_name,' . $factory->slug . ',slug,deleted_at,NULL',
            'factory_short_name' => 'required|max:32|unique:factories,factory_short_name,' . $factory->slug . ',slug,deleted_at,NULL',
            'factory_reg_number' => 'required|max:32|unique:factories,factory_reg_number,' . $factory->slug . ',slug,deleted_at,NULL',
            'factory_location' => 'required|max:32|string',
            'factory_office_slug' => 'required|string|exists:offices,slug,deleted_at,NULL',
            'factory_state' => 'required|max:32|in:' . implode(',', config('constants.states')),
            'factory_sub_account_slug' => 'required|string|exists:subaccounts,slug,deleted_at,NULL',
            'factory_power_allocation' => 'required|string|max:32',
            'factory_contact_address_1' => 'required|max:128',
            'factory_contact_address_2' => 'nullable|string|max:128',
            'factory_pincode' => 'required|max:10',
            'processor_types_list' => 'exclude_unless:factory_of,Prashanthi|array',
            'processor_types_list.*.processing_type' => 'required|distinct|max:64',
            'processor_types_list.*.processing_capacity' => 'required|max:24',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $factory->update($request->except('processor_types_list'));
            if ($request->factory_of == 'Prashanthi') {
                if ($request->filled('processor_types_list') && sizeof($request->processor_types_list) > 0) {
                    foreach ($request->processor_types_list as $processor_types) {
                        $update_processor_types[] = $processor_types['processing_type'];
                        $processor_type = ['factory_slug' => $factory->slug, 'factory_processing_types' => $processor_types['processing_type'], 'factory_processing_capacity' => $processor_types['processing_capacity']];
                        $update_already_exist = FactoryProcessing::where([
                            ['factory_processing_types', '=', $processor_types['processing_type']],
                            ['factory_slug', '=', $factory->slug]])->first();
                        if ($update_already_exist) {
                            $update_already_exist->update($processor_type);
                        } else {
                            FactoryProcessing::create($processor_type);
                        }
                        $get_exist_processor_types = FactoryProcessing::where('factory_slug', '=', $factory->slug)->pluck('factory_processing_types')->toArray();
                        $delete_processor_types = array_diff($get_exist_processor_types, $update_processor_types);
                        if (sizeof($delete_processor_types) > 0) {
                            FactoryProcessing::where('factory_slug', '=', $factory->slug)->whereIn('factory_processing_types', $delete_processor_types)->delete();
                        }
                    }
                } else {
                    $factory->factoryProcessing()->delete();
                }
            } else {
                $factory->factoryProcessing()->delete();
            }
            Factory::updateList();

            return redirect()->route('admin.factory')
                ->with('success', 'Factory updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factory  $factory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factory $factory)
    {
        if (!\Helper::userAccess('office-factory-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.factory');
        }

        $factory->factoryProcessing()->delete();
        $factory->delete();
        Factory::updateList();

        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }
}