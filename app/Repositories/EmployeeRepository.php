<?php
namespace App\Repositories;

use App\Models\Employee;
use Carbon\Carbon;

class EmployeeRepository implements EmployeeInterface
{
    private $model;

    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->paginate(5);
    }

    public function getRoleBasedUsers()
    {
        return $this->model->get();
    }

    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function validate($request,$id = 0)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:100',
            'gender' => 'required|max:100',
            'dob' => 'required|max:100',
            'email' => 'required|max:100|unique:users',
            'aadhar_no' => 'required|max:14|unique:users',
            'mobile' => 'required',
        ]);

        return $validator;
    }

    public function store($request)
    {
        $user = new $this->model();    
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->religion = $request->religion;
        $user->dob = Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d');;
        $user->email = $request->email;
        $user->employee_no = $request->employee_no;
        $user->aadhar_no = $request->aadhar_no;
        $user->nationality = $request->nationality;
        $user->job_type = $request->job_type;
        $user->job_category = $request->job_category;
        $user->work_location_type = $request->work_location_type;
        $user->work_location_id = $request->work_location_id;
        $user->address_line_1 = $request->address_line_1;
        $user->address_line_2 = $request->address_line_2;
        $user->city = $request->city;
        $user->district = $request->district;
        $user->state = $request->state;
        $user->pincode = $request->pincode;
        $user->mobile = $request->mobile;
        $user->whatsapp = $request->whatsapp;

        if($request->join_date)
            $user->join_date = Carbon::createFromFormat('d-m-Y', $request->join_date)->format('Y-m-d');
                 
        $user->status = 'pending';
        $user->identification_file = json_encode($request['files']);

        $user->save();

        return $user;
   }

    public function update($id, $request)
    {
        $user = $this->model->find($id);  
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->religion = $request->religion;
        $user->dob = Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d');;
        $user->email = $request->email;
        $user->employee_no = $request->employee_no;
        $user->aadhar_no = $request->aadhar_no;
        $user->nationality = $request->nationality;
        $user->job_type = $request->job_type;
        $user->job_category = $request->job_category;
        $user->work_location_type = $request->work_location_type;
        $user->work_location_id = $request->work_location_id;
        $user->address_line_1 = $request->address_line_1;
        $user->address_line_2 = $request->address_line_2;
        $user->city = $request->city;
        $user->district = $request->district;
        $user->state = $request->state;
        $user->pincode = $request->pincode;
        $user->mobile = $request->mobile;
        $user->whatsapp = $request->whatsapp;

        if($request->join_date)
            $user->join_date = Carbon::createFromFormat('d-m-Y', $request->join_date)->format('Y-m-d');
                 
        $user->status = $request->status;
        $user->identification_file = json_encode($request['files']);

        $user->save();

        return $user;
    }

    public function delete($id)
    {
        $this->getById($id)->delete();
        return true;
    }

    public function edit($id)
    {
        return $this->getById($id);
    }

    public function updatePassword($request){
        $validated = $request->validate([
            'password' => 'required',
        ]);

        //Update password
        $user = $this->model::find($request->id);
        $user->password = \Hash::make($request->password);
        $user->save();

        return $user;
    }

}