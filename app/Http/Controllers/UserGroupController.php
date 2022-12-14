<?php

namespace App\Http\Controllers;

use App\Models\UserGroup;
use Illuminate\Http\Request;
use Session;

class UserGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $breadcrumbs = [
        ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Settings"],
    ];

    public function index()
    {
        if (!\Helper::userAccess('user-group-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $this->breadcrumbs[] = ['name' => "User Group"];

        return view('admin.usergroup.index', [
            'breadcrumbs' => $this->breadcrumbs]);

    }

    public function listUserGroups(Request $request)
    {
        if (!\Helper::userAccess('user-group-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = UserGroup::query();
        $query->select('id', 'name', 'slug');

        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%" . $search . "%");
                    $q->orWhere('slug', 'like', "%" . $search . "%");
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
                'edit' => \Helper::userAccess('user-group-edit'),
                'delete' => \Helper::userAccess('user-group-delete'),
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
        if (!\Helper::userAccess('user-group-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.usergroup');
        }

        $this->breadcrumbs[] = ['name' => "User Group", "link" => '/admin/usergroup'];
        $this->breadcrumbs[] = ['name' => "Create User Group"];

        return view('admin.usergroup.create', [
            'breadcrumbs' => $this->breadcrumbs,
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
        if (!\Helper::userAccess('user-group-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.usergroup');
        }

        $validator_messages = [
            'name.string' => 'Please enter valid user group name',
        ];
        $validator_conditions = [
            'name' => 'required|max:32|string|unique:user_groups,name,NULL,id',
        ];
        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $data = ['name' => $request->name];
            UserGroup::create($data);

            return redirect()->route('admin.usergroup')
                ->with('success', 'User Group created successfully.');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User Group $role
     * @return \Illuminate\Http\Response
     */
    public function edit(UserGroup $usergroup)
    {
        if (!\Helper::userAccess('user-group-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.usergroup');
        }

        $this->breadcrumbs[] = ['name' => "User Group", "link" => '/admin/usergroup'];
        $this->breadcrumbs[] = ['name' => "Edit User Group"];

        return view('admin.usergroup.create', [
            'breadcrumbs' => $this->breadcrumbs,
            'usergroup' => $usergroup,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User Group $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserGroup $usergroup)
    {
        if (!\Helper::userAccess('user-group-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.usergroup');
        }

        $validator_messages = [
            'name.string' => 'Please enter valid user group name',
        ];
        $validator_conditions = [
            'name' => 'required|max:32|string|unique:user_groups,name,' . $usergroup->id . ',id',
        ];

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $data = ['name' => $request->name];
            $usergroup->update($data);

            return redirect()->route('admin.usergroup')
                ->with('success', 'User Group updated successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User Group $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserGroup $usergroup)
    {
        if (!\Helper::userAccess('user-group-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.usergroup');
        }

        $usergroup->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }

}