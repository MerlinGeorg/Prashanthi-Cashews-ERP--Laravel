<?php
  
namespace App\Http\Controllers;


use App\Models\{ShipperDetails};
use App\Http\Resources\StockyardResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Config;

class ShipperDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function index()
    {           
        if(!\Helper::userAccess('office-shipper-details-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['name' => "Shipper Details"]
        ];
        return view('admin.shipper-details.index', [
            'breadcrumbs' => $breadcrumbs]);
        
    }

    public function listShipperDetails(Request $request)
    {           
        if(!\Helper::userAccess('office-shipper-details-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }
        
        $start = $request->has('start')? $request->start:0;
        $rowperpage = $request->has('length')? $request->length:10;
        $query = ShipperDetails::query();
        $query->select('slug', 'shipper_company_name', 'shipper_location', 'shipper_contact_address_1', 'shipper_contact_address_2');
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search!='') {
                $query->where(function ($q) use ($search) {
                    $q->where('shipper_company_name', 'like', "%".$search."%");
                    $q->orWhere('shipper_location', 'like', '%'.$search.'%');
                    $q->orWhere('shipper_contact_address_1', 'like', '%'.$search.'%');
                    $q->orWhere('shipper_contact_address_2', 'like', '%'.$search.'%');
                });
            }   
        }
        if ($request->has('columns') && $request->has('order')) {
            $order_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $sort_by = $columnName_arr[$order_arr[0]['column']]['data'];
            $sort_type = $order_arr[0]['dir'];
            if ($sort_by!='' && $sort_type!='') {
                $query->orderBy($sort_by, $sort_type);
            }
        }
        $iTotalRecords = $query->count();
        $data = $query->skip($start)->take($rowperpage)->get();
        
        //Add action ACL
        $data->map(function($item){
            return $item['action'] = [
                'view' => \Helper::userAccess('office-shipper-details-view'),
                'edit' => \Helper::userAccess('office-shipper-details-edit'),
                'delete' => \Helper::userAccess('office-shipper-details-delete')
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
        if(!\Helper::userAccess('office-shipper-details-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.shipper-details');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/shipper-details", 'name' => "Shipper Details"],['name' => "Create Shipper Details"]
        ];
        return view('admin.shipper-details.create', [
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
        if(!\Helper::userAccess('office-shipper-details-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.shipper-details');
        }
        
        $validator_messages = [
        ];
        $validator_conditions = [
            'shipper_company_name' => 'required|max:32|string|unique:stockyards,stockyard_name,NULL,id,deleted_at,NULL',
            'shipper_location' => 'required|string|max:32',
            'shipper_contact_address_1' => 'required|string|max:128',
            'shipper_contact_address_2' => 'string|nullable|max:128'
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        } else {
            // print_r($request->all());exit;
            ShipperDetails::create($request->all());
            return redirect()->route('admin.shipper-details')
                        ->with('success','Office created successfully.');
        }
        
    }
     
    /**
     * Display the specified resource.
     *
     * @param  \App\ShipperDetails  $shipper_details
     * @return \Illuminate\Http\Response
     */
    public function show(ShipperDetails $shipper_details)
    {           
        if(!\Helper::userAccess('office-shipper-details-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.shipper-details');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/shipper-details", 'name' => "Shipper Details"],['name' => "Shipper Details"]
        ];
        // print_r($stockyard->subaccount->slug);exit;
        return view('admin.shipper-details.show',compact('shipper_details'), [
            'breadcrumbs' => $breadcrumbs]);
    } 
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ShipperDetails  $shipper_details
     * @return \Illuminate\Http\Response
     */
    public function edit(ShipperDetails $shipper_details)
    {           
        if(!\Helper::userAccess('office-shipper-details-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.shipper-details');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/shipper-details", 'name' => "Shipper Details"],['name' => "Edit Shipper Details"]
        ];
        return view('admin.shipper-details.edit',compact('shipper_details'), [
            'breadcrumbs' => $breadcrumbs]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ShipperDetails  $shipper_details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShipperDetails $shipper_details)
    {           
        if(!\Helper::userAccess('office-shipper-details-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.shipper-details');
        }

        $validator_messages = [
        ];
        $validator_conditions = [
            'shipper_company_name' => 'required|max:32|string|unique:stockyards,stockyard_name,'.$shipper_details->slug.',id,deleted_at,NULL',
            'shipper_location' => 'required|string|max:32',
            'shipper_contact_address_1' => 'required|string|max:128',
            'shipper_contact_address_2' => 'string|nullable|max:128'
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        } else {
            $shipper_details->update($request->all());
        
            return redirect()->route('admin.shipper-details')
                        ->with('success','Stockyard updated successfully');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ShipperDetails  $shipper_details
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShipperDetails $shipper_details)
    {           
        if(!\Helper::userAccess('office-shipper-details-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.shipper-details');
        }

        $shipper_details->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]); 
    }
}