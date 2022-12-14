<?php
  
namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Config;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $breadcrumbs = [
        ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"],['link' => "javascript:void(0)", 'name' => "ACL"]
    ];

    public function index()
    {           
        if(!\Helper::userAccess('office-office-resource-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $this->breadcrumbs[] = ['name' => "Resources"];
        
        return view('admin.resource.index',[
            'breadcrumbs' => $this->breadcrumbs]);
        
    }

    public function listResources(Request $request)
    {           
        if(!\Helper::userAccess('office-office-resource-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }
        
        $start = $request->has('start')? $request->start:0;
        $rowperpage = $request->has('length')? $request->length:10;
        $query = Resource::query();
        $query->filterByWorkLocation();        
        $query->select('id','resource_name','slug','work_location_type');
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search!='') {
                $query->where(function ($q) use ($search) {
                    $q->where('resource_name', 'like', "%".$search."%");
                    $q->orWhere('slug', 'like', "%".$search."%");
                    $q->orWhere('work_location_type', 'like', "%".$search."%");
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
        $work_location_types = Config::get('constants.work_location_types');

        //Add action ACL
        $data->map(function($item) use ($work_location_types){
            $item['work_location_type'] = $work_location_types[$item['work_location_type']] ?? '';
            return $item['action'] = [
                'view' => \Helper::userAccess('office-office-resource-view'),
                'edit' => \Helper::userAccess('office-office-resource-edit'),
                'delete' => \Helper::userAccess('office-office-resource-delete')
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
        if(!\Helper::userAccess('office-office-resource-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.resource');
        }

        $this->breadcrumbs[] = ['name' => "Create Resource"];
        $work_location_types = Config::get('constants.work_location_types');
        
        return view('admin.resource.create',[
            'breadcrumbs' => $this->breadcrumbs,
            'work_location_types' => $work_location_types
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
        if(!\Helper::userAccess('office-office-resource-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.resource');
        }
        
        $validator_messages = [
            'resource_name.string' => 'Please enter valid resource name',
            'work_location_type.string' => 'Please select work location type'
        ];
        $validator_conditions = [
            'resource_name' => 'required|max:32|string|unique:resources,resource_name,NULL,id',
            'work_location_type' => 'required'
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        } else {
            $resource_data = ['resource_name' => $request->resource_name,'work_location_type' => $request->work_location_type];
            $resource = Resource::create($resource_data);
            
            return redirect()->route('admin.resource')
                        ->with('success','Resource created successfully.');
        }
        
    }
     
    /**
     * Display the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {                  
        if(!\Helper::userAccess('office-office-resource-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.resource');
        }
    } 
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resource $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {           
        if(!\Helper::userAccess('office-office-resource-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.resource');
        }

        $this->breadcrumbs[] = ['name' => "Edit Resource"];
        $work_location_types = Config::get('constants.work_location_types');
        
        return view('admin.resource.create',[
            'breadcrumbs' => $this->breadcrumbs,
            'resource' => $resource,
            'work_location_types' => $work_location_types
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resource $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {           
        if(!\Helper::userAccess('office-office-resource-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.resource');
        }

        $validator_messages = [
            'resource_name.string' => 'Please enter valid resource name',
            'work_location_type.string' => 'Please select work location type'
        ];
        $validator_conditions = [
            'resource_name' => 'required|max:32|string|unique:resources,resource_name,'.$resource->id.',id',
            'work_location_type' => 'required'
        ];
        
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        } else {
            $resource_data = ['resource_name' => $request->resource_name,'work_location_type' => $request->work_location_type];
            $resource->update($resource_data);
            
            return redirect()->route('admin.resource')
                        ->with('success','Resource updated successfully');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resource $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {           
        if(!\Helper::userAccess('office-office-resource-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.resource');
        }

        $resource->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]); 
    }

    public function privileges()
    {                   
        if(!\Helper::userAccess('office-office-resource-privilege')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.resource');
        }
        
        $this->breadcrumbs[] = ['name' => "Resource Privileges"];

        $resources = Resource::all();
        $permissions = Permission::all();

        return view('admin.resource.privileges',[
            'breadcrumbs' => $this->breadcrumbs,
            'resources' => $resources,
            'permissions' => $permissions
        ]);
    }
}