<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\Nationality;
use App\Models\Office;
use App\Models\PackageCenter;
use App\Models\Role;
use App\Models\Stockyard;
use App\Repositories\UserInterface;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        $statuses = Config::get('constants.statuses');
        $work_location_types = Config::get('constants.work_location_types');
        $user_groups = UserGroup::whereNotIn('slug', ['system-administrator'])->pluck('name', 'slug');
        $states = Config::get('constants.states');
        $religions = Config::get('constants.religions');
        $nationalities = Nationality::pluck('name', 'slug')->toArray();
        return view('user.register', compact('statuses', 'work_location_types', 'user_groups', 'nationalities', 'religions', 'states'));
    }

    public function store(Request $request)
    {

        $validator = $this->user->validate($request, 0);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 200);
        }

        if ($this->user->store($request)) {
            return response()->json(['success' => true, 'message' => 'Staff registered successfully!'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Staff not registrered! Something error occured'], 200);
        }
    }

    public function upload(Request $request)
    {

        if ($request->hasFile('file')) {
            $path = Storage::disk()->putFile(env('APP_ENV') . '/staff/identification file/' . date('Y') . '/' . date('m'), $request->file('file'), 'public');
            return response()->json(['success' => true, 'path' => $path], 200);
        } else {
            return response()->json(['success' => false, 'error' => 'File not uploaded! Something error occured'], 400);
        }
    }

    public function uploadProfileImage(Request $request)
    {

        if ($request->hasFile('profile')) {
            $path = Storage::disk()->putFile(env('APP_ENV') . '/staff/profile image/' . date('Y') . '/' . date('m'), $request->file('profile'), 'public');
            return response()->json(['success' => true, 'path' => $path], 200);
        } else {
            return response()->json(['success' => false, 'error' => 'File not uploaded! Something error occured'], 400);
        }
    }

    public function workLocations(Request $request, $type)
    {
        if ($type == "office") {
            $work_locations = Office::select('slug', 'office_name as name')->filterbyOffice();

        } else if ($type == "stockyard") {
            $work_locations = Stockyard::select('slug', 'stockyard_name as name')->filterbyOffice();

        } else if ($type == "factory") {
            $work_locations = Factory::select('slug', 'factory_name as name')->filterbyOffice();

        } else if ($type == "package") {
            $work_locations = PackageCenter::select('slug', 'package_center_name as name')->filterbyOffice();

        }

        return response()->json($work_locations->get());
    }

    public function roles(Request $request, $work_location_type)
    {
        $roles = Role::select('slug', 'name')->where('work_location_type', $work_location_type)->get();

        return response()->json($roles);
    }

}