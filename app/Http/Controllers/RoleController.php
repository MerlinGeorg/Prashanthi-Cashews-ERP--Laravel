<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Resource;
use App\Models\Role;
use Config;
use Illuminate\Http\Request;
use Session;

class RoleController extends Controller
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
        if (!\Helper::userAccess('office-role-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $this->breadcrumbs[] = ['name' => "Roles"];

        return view('admin.role.index', [
            'breadcrumbs' => $this->breadcrumbs]);

    }

    public function listRoles(Request $request)
    {
        if (!\Helper::userAccess('office-role-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = Role::query();
        $query->select('id', 'name', 'slug', 'work_location_type');
        //$query->filterByWorkLocation();
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%" . $search . "%");
                    $q->orWhere('slug', 'like', "%" . $search . "%");
                    $q->orWhere('work_location_type', 'like', "%" . $search . "%");
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
                'view' => \Helper::userAccess('office-role-view'),
                'edit' => \Helper::userAccess('office-role-edit'),
                'delete' => \Helper::userAccess('office-role-delete'),
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
        if (!\Helper::userAccess('office-role-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.role');
        }

        $this->breadcrumbs[] = ['name' => "Create Role"];

        $work_location_types = Config::get('constants.work_location_types');

        return view('admin.role.create', [
            'breadcrumbs' => $this->breadcrumbs,
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
        if (!\Helper::userAccess('office-role-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.role');
        }

        $validator_messages = [
            'name.string' => 'Please enter valid role name',
            'work_location_type.string' => 'Please select work location type',
        ];
        $validator_conditions = [
            'name' => 'required|max:32|string|unique:roles,name,NULL,id',
            'work_location_type' => 'required',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $role_data = ['name' => $request->name, 'work_location_type' => $request->work_location_type];
            $role = Role::create($role_data);

            return redirect()->route('admin.role')
                ->with('success', 'Role created successfully.');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if (!\Helper::userAccess('office-role-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.role');
        }

        $this->breadcrumbs[] = ['name' => "Edit Role"];
        $work_location_types = Config::get('constants.work_location_types');

        return view('admin.role.create', [
            'breadcrumbs' => $this->breadcrumbs,
            'role' => $role,
            'work_location_types' => $work_location_types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if (!\Helper::userAccess('office-role-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.role');
        }

        $validator_messages = [
            'name.string' => 'Please enter valid role name',
            'work_location_type.string' => 'Please select work location type',
        ];
        $validator_conditions = [
            'name' => 'required|max:32|string|unique:roles,name,' . $role->id . ',id',
            'work_location_type' => 'required',
        ];

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $role_data = ['name' => $request->name, 'work_location_type' => $request->work_location_type];
            $role->update($role_data);

            return redirect()->route('admin.role')
                ->with('success', 'Role updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if (!\Helper::userAccess('office-role-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.role');
        }

        $role->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }

    // public function privileges()
    // {
    //     if(!\Helper::userAccess('office-role-privilege-view')) {
    //         Session::flash('error', trans('locale.NotAuthorized'));
    //         return redirect()->route('admin.role');
    //     }

    //     $this->breadcrumbs[] = ['name' => "Role Privileges"];

    //     $roles = Role::filterByWorkLocation()->get();

    //     return view('admin.role.privileges',[
    //         'breadcrumbs' => $this->breadcrumbs,
    //         'roles' => $roles,
    //     ]);
    // }

    public function editPrivileges($slug)
    {
        if (!\Helper::userAccess('office-role-privilege-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.role');
        }

        $this->breadcrumbs[] = ['link' => "javascript:void(0)", 'name' => "Role Privileges"];
        $this->breadcrumbs[] = ['name' => "View / Edit Previlege"];

        $role = Role::where('slug', $slug)->first();

        $resources = Resource::byWorkLocation($role->work_location_type)->orderBy('work_location_type', 'asc')->get();

        $arr_role_permissions = $role->permissions()->select('work_location_type', 'resource_slug', 'slug')->get();
        $role_permissions = [];

        $work_location_types = Config::get('constants.work_location_types');

        $acl = [];
        foreach ($resources as $resource) {
            $acl[$resource->work_location_type][] = $resource;
        }

        foreach ($arr_role_permissions as $resource) {
            $role_permissions[$resource->resource_slug][] = $resource->slug;
        }

        return view('admin.role.edit-privileges', [
            'breadcrumbs' => $this->breadcrumbs,
            'role' => $role,
            'acl' => $acl,
            'role_permissions' => $role_permissions,
            'change_access' => \Helper::userAccess('office-role-privilege-change'),
            'work_location_types' => $work_location_types,
        ]);
    }

    public function savePrivileges(Request $request, $slug)
    {
        if (!\Helper::userAccess('office-role-privilege-change')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.role');
        }

        $this->breadcrumbs[] = ['link' => "javascript:void(0)", 'name' => "Role Privileges"];
        $this->breadcrumbs[] = ['name' => "Edit Previlege"];

        $role = Role::where('slug', $slug)->first();

        $permissions = Permission::whereIn('slug', $request->permissions ?? [])->pluck('id')->toArray();

        $role->syncPermissions($permissions);

        return redirect()->route('admin.role');
    }

}
