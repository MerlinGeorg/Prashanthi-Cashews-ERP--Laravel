<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Nationality;
use App\Models\Role;
use App\Models\User;
use App\Models\UserGroup;
use App\Repositories\UserInterface;
use Config;
use Illuminate\Http\Request;
use Session;

class UserController extends Controller
{
    protected $user;
    protected $breadcrumbs = [
        ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"],
    ];

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    // User List Page
    public function index()
    {
        if (!\Helper::userAccess('office-staff-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $this->breadcrumbs[] = ['name' => "Staff"];
        $breadcrumbs = $this->breadcrumbs;

        $total_users = User::filterbyOffice()->count();
        $active_users = User::filterbyOffice()->activeUsers()->count();
        $pending_users = User::filterbyOffice()->pendingUsers()->count();
        $inactive_users = User::filterbyOffice()->inactiveUsers()->count();

        return view('admin.user.index', compact('breadcrumbs', 'total_users', 'active_users', 'pending_users', 'inactive_users'));
    }

    // List user details for datatable
    public function listUsers(Request $request)
    {
        if (!\Helper::userAccess('office-staff-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        $statusClass = [
            'Pending' => 'badge-light-warning',
            'Active' => 'badge-light-success',
            'Inactive' => 'badge-light-danger',
        ];

        $users = User::filterbyOffice()->get();
        $statuses = Config::get('constants.statuses');
        $output = [];

        foreach ($users as $i => $user) {

            $output[$i]['id'] = $user->id;
            $output[$i]['name'] = $user->name;
            $output[$i]['mobile'] = $user->mobile;
            $output[$i]['employee_no'] = $user->employee_no;
            $output[$i]['user_group'] = $user->userGroup->name ?? '';
            $output[$i]['username'] = $user->username ?? '';
            //$output[$i]['join_date'] = $user->join_date ? Carbon::createFromFormat('Y-m-d',$user->join_date)->format('d-m-Y') : '';
            $output[$i]['roles'] = '<span class="badge btn-outline-success text-success">' .
                (implode('</span> <span class="badge btn-outline-success text-success">', $user->getRoleNames()->toArray())) .
                '</span>';
            $output[$i]['status'] = $statuses[$user->status];
            $output[$i]['statusClass'] = $statusClass;
            $output[$i]['slug'] = $user->slug;
            $output[$i]['action'] = [
                'view' => \Helper::userAccess('office-staff-view'),
                'edit' => \Helper::userAccess('office-staff-edit'),
                'delete' => \Helper::userAccess('office-staff-delete'),
                'change-password' => \Helper::userAccess('office-staff-change-password'),
            ];
        }

        return response()->json(['data' => $output]);
    }

    // User Create Page
    public function create()
    {
        if (!\Helper::userAccess('office-staff-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.staff');
        }

        $this->breadcrumbs[] = ['link' => "admin/staff", 'name' => "Staff"];
        $this->breadcrumbs[] = ['name' => "Create Staff"];
        $breadcrumbs = $this->breadcrumbs;

        $roles = Role::all();
        $statuses = Config::get('constants.statuses');
        $types = Config::get('constants.work_location_types');
        $work_location_types = \Auth::user()->isROStaff() ? [\Auth::user()->work_location_type => $types[\Auth::user()->work_location_type]] : $types;
        $user_groups = UserGroup::whereNotIn('slug', ['system-administrator'])->pluck('name', 'slug');
        $states = Config::get('constants.states');
        $religions = Config::get('constants.religions');
        $nationalities = Nationality::pluck('name', 'slug')->toArray();

        return view('admin.user.create', compact('breadcrumbs', 'statuses', 'work_location_types', 'user_groups', 'nationalities', 'religions', 'states', 'roles'));
    }

    // Edit user details
    public function edit(Request $request, User $staff)
    {
        if (!\Helper::userAccess('office-staff-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.staff');
        }

        $this->breadcrumbs[] = ['link' => "admin/staff", 'name' => "Staff"];
        $this->breadcrumbs[] = ['name' => "Edit Staff"];
        $breadcrumbs = $this->breadcrumbs;

        $statuses = Config::get('constants.statuses');
        $types = Config::get('constants.work_location_types');
        $work_location_types = \Auth::user()->isROStaff() ? [\Auth::user()->work_location_type => $types[\Auth::user()->work_location_type]] : $types;
        $user_groups = UserGroup::whereNotIn('slug', ['system-administrator'])->pluck('name', 'slug');
        $states = Config::get('constants.states');
        $religions = Config::get('constants.religions');
        $nationalities = Nationality::pluck('name', 'slug')->toArray();
        $user_roles = $staff->getRoleIds();
        $roles = Role::where('work_location_type', $staff->work_location_type)->get();

        return view('admin.user.edit', compact('breadcrumbs', 'roles', 'staff', 'statuses', 'work_location_types', 'user_groups', 'nationalities', 'religions', 'states', 'user_roles'));
    }

    // Update User Details
    public function update(Request $request, User $staff)
    {
        if (!\Helper::userAccess('office-staff-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.staff');
        }

        $validator = $this->user->validate($request, $staff->id);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 200);
        }

        if ($this->user->update($staff->slug, $request)) {
            Session::flash('success', 'User updated successfully!');
            return response()->json(['success' => true, 'message' => 'Staff details updated!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update Staff details!']);
        }
    }

    //User Permission
    public function userPermission(Request $request, $id)
    {
        if (!\Helper::userAccess('office-staff-permission')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.staff');
        }

        $user_permissions = $this->user->userPermissions($id);
        $user = $this->user->getById($id);

        return view('admin.user.permission', compact('id', 'user_permissions', 'user'));
    }

    //Submit User Permission
    public function submitUserPermission(Request $request)
    {
        if (!\Helper::userAccess('office-staff-permission')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.staff');
        }

        $this->user->saveUserPermissions($request);

        Session::flash('success', 'User permissions updated successfully!');
        return redirect()->route('admin.user');
    }

    //User Change Password
    public function changePassword(Request $request, $slug)
    {
        if (!\Helper::userAccess('office-staff-change-password')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.staff');
        }

        $this->breadcrumbs[] = ['link' => "admin/staff", 'name' => "Staff"];
        $this->breadcrumbs[] = ['name' => "Change Password"];
        $breadcrumbs = $this->breadcrumbs;

        $staff = User::where('slug', $slug)->first();

        return view('admin.user.change-password', compact('staff', 'breadcrumbs'));
    }

    //Submit User Change Password
    public function submitChangePassword(Request $request)
    {
        if (!\Helper::userAccess('office-staff-change-password')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.staff');
        }

        $this->user->updatePassword($request);

        Session::flash('success', 'Password changed successfully!');
        return redirect()->route('admin.staff');
    }

    public function show(User $staff)
    {
        if (!\Helper::userAccess('office-staff-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.staff');
        }

        $breadcrumbs = [
            ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "admin/staff", 'name' => "Staff"], ['name' => "Staff Details"],
        ];

        $statuses = Config::get('constants.statuses');
        $work_location_types = Config::get('constants.work_location_types');
        $states = Config::get('constants.states');
        $religions = Config::get('constants.religions');
        $nationalities = Nationality::pluck('name', 'slug')->toArray();

        if ($staff) {
            return view('admin.user.show', compact('staff', 'breadcrumbs', 'statuses', 'work_location_types', 'nationalities', 'religions', 'states'));
        }
    }

    public function destroy($slug)
    {
        if (!\Helper::userAccess('office-staff-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.staff');
        }

        $user = User::where('slug', $slug)->first();
        $user->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }
}