<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Resource;
use Config;
use Illuminate\Http\Request;
use Session;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $breadcrumbs = [
        ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "javascript:void(0)", 'name' => "ACL"],
    ];

    public function index()
    {
        if (!\Helper::userAccess('office-permission-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $this->breadcrumbs[] = ['name' => "Permissions"];

        return view('admin.permission.index', [
            'breadcrumbs' => $this->breadcrumbs]);

    }

    public function listPermissions(Request $request)
    {
        if (!\Helper::userAccess('office-permission-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = Permission::query();
        $query->join('resources', 'resources.slug', '=', 'permissions.resource_slug');
        $query->select('permissions.id', 'permissions.name', 'permissions.slug',
            'resources.resource_name as resource_slug', 'permissions.work_location_type');
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%" . $search . "%");
                    $q->orWhere('permissions.slug', 'like', "%" . $search . "%");
                    $q->orWhere('permissions.resource_slug', 'like', "%" . $search . "%");
                    $q->orWhere('permissions.work_location_type', 'like', "%" . $search . "%");
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

        $work_location_types = Config::get('constants.work_location_types');

        //Add action ACL
        $data->map(function ($item) use ($work_location_types) {
            $item['work_location_type'] = $work_location_types[$item['work_location_type']] ?? '';
            return $item['action'] = [
                'view' => \Helper::userAccess('office-permission-view'),
                'edit' => \Helper::userAccess('office-permission-edit'),
                'delete' => \Helper::userAccess('office-permission-delete'),
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
        if (!\Helper::userAccess('office-permission-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.permission');
        }

        $this->breadcrumbs[] = ['name' => "Create Permission"];

        $resources = Resource::filterByWorkLocation()->get();

        $work_location_types = Config::get('constants.work_location_types');

        return view('admin.permission.create', [
            'breadcrumbs' => $this->breadcrumbs,
            'resources' => $resources,
            'work_location_types' => $work_location_types,
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
        if (!\Helper::userAccess('office-permission-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.permission');
        }

        $validator_messages = [
            'name.string' => 'Please enter valid permission name',
            'resource_slug.string' => 'Please select a resource',
        ];
        $validator_conditions = [
            'name' => 'required|max:32|string',
            'resource_slug' => 'required|max:32|string',
        ];

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {

            $resource = Resource::getBySlug($request->resource_slug);

            $permission_data = [
                'name' => $request->name,
                'guard_name' => 'web',
                'resource_slug' => $request->resource_slug,
                'work_location_type' => $resource->work_location_type,
            ];

            $permission = Permission::create($permission_data);

            return redirect()->route('admin.permission')
                ->with('success', 'Permission created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        if (!\Helper::userAccess('office-permission-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.permission');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        if (!\Helper::userAccess('office-permission-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.permission');
        }

        $this->breadcrumbs[] = ['name' => "Edit Permission"];
        $resources = Resource::all();
        $work_location_types = Config::get('constants.work_location_types');

        return view('admin.permission.create', [
            'breadcrumbs' => $this->breadcrumbs,
            'permission' => $permission,
            'resources' => $resources,
            'work_location_types' => $work_location_types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        if (!\Helper::userAccess('office-permission-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.permission');
        }

        $validator_messages = [
            'name.string' => 'Please enter valid permission name',
            'resource_slug.string' => 'Please select a resource',
        ];
        $validator_conditions = [
            'name' => 'required|max:32|string',
            'resource_slug' => 'required|max:32|string',
        ];

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {

            $resource = Resource::getBySlug($request->resource_slug);

            $permission_data = [
                'name' => $request->name,
                'guard_name' => 'web',
                'resource_slug' => $request->resource_slug,
                'work_location_type' => $resource->work_location_type,
            ];
            $permission->update($permission_data);

            return redirect()->route('admin.permission')
                ->with('success', 'Permission updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if (!\Helper::userAccess('office-permission-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.permission');
        }

        $permission->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }
}