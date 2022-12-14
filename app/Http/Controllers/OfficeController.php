<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;
use Session;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!\Helper::userAccess('office-office-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['name' => "Office"],
        ];
        return view('admin.office.index', [
            'breadcrumbs' => $breadcrumbs]);

    }

    public function listOffice(Request $request)
    {
        if (!\Helper::userAccess('office-office-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = Office::query();
        $query->filterbyOffice();

        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('office_name', 'like', "%" . $search . "%");
                    $q->orWhere('office_reg_number', 'like', '%' . $search . '%');
                    $q->orWhere('office_short_name', 'like', '%' . $search . '%');
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
                'view' => \Helper::userAccess('office-office-view'),
                'edit' => \Helper::userAccess('office-office-edit'),
                'delete' => \Helper::userAccess('office-office-delete'),
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
        if (!\Helper::userAccess('office-office-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.office');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/office", 'name' => "Offices"], ['name' => "Create Office"],
        ];
        return view('admin.office.create', [
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
        if (!\Helper::userAccess('office-office-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.office');
        }

        $validator_messages = [
            'office_name.string' => 'Please enter valid office name',
            'office_short_name.string' => 'Please enter valid office short name',
            'office_reg_number.string' => 'Please enter valid office registration number',
        ];
        $validator_conditions = [
            'office_name' => 'required|max:32|string|unique:offices,office_name,NULL,id,deleted_at,NULL',
            'office_short_name' => 'required|string|max:32|unique:offices,office_short_name,NULL,id,deleted_at,NULL',
            'office_reg_number' => 'required|string|max:32|unique:offices,office_reg_number,NULL,id,deleted_at,NULL',
            'office_location' => 'required|string|max:32',
            'office_pincode' => 'required|string|min:6|max:6',
            'office_state' => 'required|string|max:32',
            'office_address_1' => 'required|string|max:128',
            'office_address_2' => 'nullable|string|max:128',
            'office_phone_number' => 'required|string|max:15',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            Office::create($request->all());
            return redirect()->route('admin.office')
                ->with('success', 'Office created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function show(Office $office)
    {
        if (!\Helper::userAccess('office-office-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.office');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/office", 'name' => "Offices"], ['name' => "Office Details"],
        ];
        return view('admin.office.show', compact('office'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function edit(Office $office)
    {
        if (!\Helper::userAccess('office-office-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.office');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/office", 'name' => "Offices"], ['name' => "Edit Office"],
        ];
        return view('admin.office.edit', compact('office'), [
            'breadcrumbs' => $breadcrumbs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Office $office)
    {
        if (!\Helper::userAccess('office-office-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.office');
        }

        $validator_messages = [
            'office_name.string' => 'Please enter valid office name',
            'office_short_name.string' => 'Please enter valid office short name',
            'office_reg_number.string' => 'Please enter valid office registration number',
        ];
        $validator_conditions = [
            'office_name' => 'required|max:32|string|unique:offices,office_name,' . $office->slug . ',slug,deleted_at,NULL',
            'office_short_name' => 'required|string|max:32|unique:offices,office_short_name,' . $office->slug . ',slug,deleted_at,NULL',
            'office_reg_number' => 'required|string|max:32|unique:offices,office_reg_number,' . $office->slug . ',slug,deleted_at,NULL',
            'office_location' => 'required|string|max:32',
            'office_pincode' => 'required|string|max:10',
            'office_state' => 'required|string|max:32',
            'office_address_1' => 'required|string|max:128',
            'office_address_2' => 'nullable|string|max:128',
            'office_phone_number' => 'required|string|max:15',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $office->update($request->all());

            return redirect()->route('admin.office')
                ->with('success', 'Office updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function destroy(Office $office)
    {
        if (!\Helper::userAccess('office-office-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.office');
        }

        $office->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }

}