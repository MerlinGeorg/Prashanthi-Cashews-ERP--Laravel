<?php
namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;

class UserRepository implements UserInterface
{
    private $model;

    public function __construct(User $model)
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

    public function validate($request, $id = 0)
    {
        $rules = [
            'name' => 'required|max:100',
            'gender' => 'required|max:100',
            'dob' => 'required|max:100',
            'aadhar_no' => 'required|max:14|unique:users,aadhar_no,' . $id,
            'mobile' => 'required',
            'username' => 'required|max:50|unique:users,username,' . $id,
        ];

        if ($request->filled('email')) {
            $rules['email'] = 'max:100|unique:users,email,' . $id;
        }

        $validator = \Validator::make($request->all(), $rules);

        return $validator;
    }

    public function store($request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->religion = $request->religion;
        $user->dob = Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d');
        $user->email = $request->email;
        $user->qualification = $request->qualification;
        $user->experiences = $request->experiences;
        $user->employee_no = $request->employee_no;
        $user->aadhar_no = $request->aadhar_no;
        $user->nationality = $request->nationality;
        $user->job_type = $request->job_type;
        $user->user_group = $request->user_group;
        $user->work_location_type = $request->work_location_type;
        $user->work_location_slug = $request->work_location_slug;
        $user->address_line_1 = $request->address_line_1;
        $user->address_line_2 = $request->address_line_2;
        $user->city = $request->city;
        $user->district = $request->district;
        $user->state = $request->state;
        $user->pincode = $request->pincode;
        $user->mobile = $request->mobile;
        $user->whatsapp = $request->whatsapp;

        if ($request->join_date) {
            $user->join_date = Carbon::createFromFormat('d-m-Y', $request->join_date)->format('Y-m-d');
        }

        $user->username = $request->username;
        $user->status = 'pending';
        $user->password = \Hash::make($request->password);

        $user->identification_file = json_encode($request['files']);

        if ($request->profile_image) {
            $user->profile_image = $request->profile_image;
        }

        $user->save();

        //Assign roles
        $user->syncRoles($request->roles);

        return $user;
    }

    public function update($slug, $request)
    {
        $user = User::where('slug', $slug)->first();
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->religion = $request->religion;
        $user->dob = Carbon::createFromFormat('d-m-Y', $request->dob)->format('Y-m-d');
        $user->email = $request->email;
        $user->qualification = $request->qualification;
        $user->experiences = $request->experiences;
        $user->employee_no = $request->employee_no;
        $user->aadhar_no = $request->aadhar_no;
        $user->nationality = $request->nationality;
        $user->identification_file = $request->identification_file;
        $user->job_type = $request->job_type;
        $user->user_group = $request->user_group;
        $user->work_location_type = $request->work_location_type;
        $user->work_location_slug = $request->work_location_slug;
        $user->address_line_1 = $request->address_line_1;
        $user->address_line_2 = $request->address_line_2;
        $user->city = $request->city;
        $user->district = $request->district;
        $user->state = $request->state;
        $user->pincode = $request->pincode;
        $user->mobile = $request->mobile;
        $user->whatsapp = $request->whatsapp;
        if ($request->join_date) {
            $user->join_date = Carbon::createFromFormat('d-m-Y', $request->join_date)->format('Y-m-d');
        }

        $user->username = $request->username;
        $user->status = $request->status;

        $user->identification_file = json_encode($request['files']);

        if ($request->profile_image) {
            $user->profile_image = $request->profile_image;
        }

        $user->save();

        //Assign roles
        $user->syncRoles($request->roles);

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

    public function updatePassword($request)
    {
        $validated = $request->validate([
            'password' => 'required',
        ]);

        //Update password
        $user = $this->model::where('slug', $request->slug)->first();
        $user->password = \Hash::make($request->password);
        $user->save();

        return $user;
    }

}