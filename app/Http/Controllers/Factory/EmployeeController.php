<?php

namespace App\Http\Controllers\Factory;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\JobCategory;
use App\Models\Nationality;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Session;

class EmployeeController extends Controller
{
    protected $breadcrumbs = [
        ['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Factory"], ['link' => "javascript:void(0)", 'name' => "Manage"], ['link' => "javascript:void(0)", 'name' => "Employee"],
    ];
    // User List Page
    public function index()
    {
        if (!\Helper::userAccess('factory-employee-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('dashboard');
        }

        unset($this->breadcrumbs[count($this->breadcrumbs) - 1]['link']);
        $breadcrumbs = $this->breadcrumbs;

        $total_users = Employee::filterbyOffice('factory')->count();
        $active_users = Employee::activeUsers()->filterbyOffice('factory')->count();
        $pending_users = Employee::pendingUsers()->filterbyOffice('factory')->count();
        $inactive_users = Employee::inactiveUsers()->filterbyOffice('factory')->count();

        return view('factory.employee.index', compact('breadcrumbs', 'total_users', 'active_users', 'pending_users', 'inactive_users'));

    }

    // List user details for datatable
    public function listUsers(Request $request)
    {
        if (!\Helper::userAccess('factory-employee-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.employee');
        }

        $start = $request->has('start') ? $request->start : 0;
        $rowperpage = $request->has('length') ? $request->length : 10;
        $query = Employee::where('work_location_type', 'factory')
            ->filterbyOffice('factory');
        if ($request->has('search')) {
            $search_arr = $request->get('search');
            $search = $search_arr['value'];
            if ($search != '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%" . $search . "%");
                    $q->orWhere('employee_no', 'like', '%' . $search . '%');
                    $q->orWhere('aadhar_no', 'like', '%' . $search . '%');
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
        $query->select('*', 'employees.slug as employee_slug');
        $iTotalRecords = $query->count();
        $data = $query->skip($start)->take($rowperpage)->get();

        $statusClass = [
            'Pending' => 'badge-light-warning',
            'Active' => 'badge-light-success',
            'Inactive' => 'badge-light-danger',
        ];

        $statuses = Config::get('constants.statuses');

        $output = [];

        foreach ($data as $i => $user) {

            $output[$i]['id'] = $user->id;
            $output[$i]['name'] = $user->name;
            $output[$i]['mobile'] = $user->mobile;
            $output[$i]['employee_no'] = $user->employee_no;
            $output[$i]['user_group'] = $user->user_group;
            $output[$i]['join_date'] = $user->join_date ? $user->join_date->format('d-m-Y') : '';
            $output[$i]['status'] = $statuses[$user->status];
            $output[$i]['statusClass'] = $statusClass;
            $output[$i]['slug'] = $user->employee_slug;
            $output[$i]['job_category'] = $user->jobCategory->name ?? '';
            $output[$i]['action'] = [
                'view' => \Helper::userAccess('factory-employee-view'),
                'edit' => \Helper::userAccess('factory-employee-edit'),
                'delete' => \Helper::userAccess('factory-employee-delete'),
            ];
        }

        return response()->json([
            "iTotalRecords" => $iTotalRecords,
            "iTotalDisplayRecords" => $iTotalRecords,
            "aaData" => $output]);

    }

    public function create()
    {
        if (!\Helper::userAccess('factory-employee-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.employee');
        }

        $this->breadcrumbs[] = ['name' => "Create Employee"];
        $breadcrumbs = $this->breadcrumbs;

        $statuses = Config::get('constants.statuses');
        $types = Config::get('constants.work_location_types');
        $work_location_types = ['factory' => 'Factory']; //\Auth::user()->isROStaff() ? [\Auth::user()->work_location_type => $types[\Auth::user()->work_location_type]] : $types;

        $job_categories = JobCategory::pluck('name', 'slug');
        $states = Config::get('constants.states');
        $religions = Config::get('constants.religions');
        $nationalities = Nationality::pluck('name', 'slug')->toArray();

        return view('factory.employee.create', compact('statuses', 'work_location_types', 'job_categories', 'breadcrumbs', 'states', 'religions', 'nationalities'));
    }

    public function store(Request $request)
    {
        if (!\Helper::userAccess('factory-employee-add')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.employee');
        }

        $validator_messages = [
            'aadhar_no.string' => 'Please enter valid aadhaar number',
            'employee_no.string' => 'Please enter valid employee number',
        ];

        $validator_conditions = [
            'aadhar_no' => 'required|max:14|string|unique:employees,aadhar_no,NULL,id,deleted_at,NULL',
            'employee_no' => 'required|max:20|string|unique:employees,employee_no,NULL,id,deleted_at,NULL',
            //'email' => 'required|string|unique:employees,email,NULL,id,deleted_at,NULL',
            'name' => 'required|max:100',
            'gender' => 'required|max:100',
            'dob' => 'required|max:100',
            'mobile' => 'required',
        ];

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        } else {
            $data = $request->all();
            $data['dob'] = Carbon::createFromFormat('d-m-Y', $data['dob'])->format('Y-m-d');

            if ($data['join_date']) {
                $data['join_date'] = Carbon::createFromFormat('d-m-Y', $data['join_date'])->format('Y-m-d');
            }

            if (isset($data['files']) && $data['files']) {
                $data['identification_file'] = json_encode($data['files']);
            }

            $data['status'] = 'pending';

            if (Employee::create($data)) {
                return response()->json(['success' => 'Employee registered successfully!'], 200);
            } else {
                return response()->json(['error' => 'Employee not registrered! Something error occured'], 400);
            }
        }
    }

    public function upload(Request $request)
    {

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('public/employee/' . session()->getId());

            return response()->json(['success' => true, 'path' => $path], 200);
        } else {
            return response()->json(['success' => false, 'error' => 'File not uploaded! Something error occured'], 400);
        }
    }

    public function show(Request $request, Employee $employee)
    {
        if (!\Helper::userAccess('factory-employee-view')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.employee');
        }

        $this->breadcrumbs[] = ['name' => "View Employee"];
        $breadcrumbs = $this->breadcrumbs;

        $statuses = Config::get('constants.statuses');
        $work_location_types = Config::get('constants.work_location_types');
        $states = Config::get('constants.states');
        $religions = Config::get('constants.religions');
        $nationalities = Nationality::pluck('name', 'slug')->toArray();

        return view('factory.employee.show', compact('employee', 'statuses', 'work_location_types', 'breadcrumbs', 'states', 'religions', 'nationalities'));
    }

    public function edit(Employee $employee)
    {
        if (!\Helper::userAccess('factory-employee-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.employee');
        }

        $this->breadcrumbs[] = ['name' => "Edit Employee"];
        $breadcrumbs = $this->breadcrumbs;

        $statuses = Config::get('constants.statuses');
        $types = Config::get('constants.work_location_types');
        $work_location_types = ['factory' => 'Factory']; // \Auth::user()->isROStaff() ? [\Auth::user()->work_location_type => $types[\Auth::user()->work_location_type]] : $types;
        $job_categories = JobCategory::pluck('name', 'slug');

        $states = Config::get('constants.states');
        $religions = Config::get('constants.religions');
        $nationalities = Nationality::pluck('name', 'slug')->toArray();

        return view('factory.employee.edit', compact('employee', 'statuses', 'work_location_types', 'job_categories', 'breadcrumbs', 'states', 'religions', 'nationalities'));
    }

    public function update(Request $request, Employee $employee)
    {
        if (!\Helper::userAccess('factory-employee-edit')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.employee');
        }

        $validator_messages = [
            'aadhar_no.string' => 'Please enter valid aadhar number',
            'employee_no.string' => 'Please enter valid employee number',
        ];

        $validator_conditions = [
            'aadhar_no' => 'required|max:14|string|unique:employees,aadhar_no,' . $employee->slug . ',slug,deleted_at,NULL',
            'employee_no' => 'required|max:20|string|unique:employees,employee_no,' . $employee->slug . ',slug,deleted_at,NULL',
            'name' => 'required|max:100',
            'gender' => 'required|max:100',
            'dob' => 'required|max:100',
            'email' => 'required|max:100',
            'mobile' => 'required',
        ];

        $validator = \Validator::make(request()->all(), $validator_conditions, $validator_messages);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        } else {
            $data = $request->all();
            $data['dob'] = Carbon::createFromFormat('d-m-Y', $data['dob'])->format('Y-m-d');

            if ($data['join_date']) {
                $data['join_date'] = Carbon::createFromFormat('d-m-Y', $data['join_date'])->format('Y-m-d');
            }

            if (isset($data['files']) && $data['files']) {
                $data['identification_file'] = json_encode($data['files']);
            }

            if ($employee->update($data)) {
                return response()->json(['success' => 'Employee details updated successfully!'], 200);
            } else {
                return response()->json(['error' => 'Employee details not updated! Something error occured'], 400);
            }
        }
    }

    public function destroy(Employee $employee)
    {
        if (!\Helper::userAccess('factory-employee-delete')) {
            Session::flash('error', trans('locale.NotAuthorized'));
            return redirect()->route('admin.employee');
        }

        $employee->delete();
        return response()->json([
            "status" => 'Success',
            "code" => 200]);
    }

}