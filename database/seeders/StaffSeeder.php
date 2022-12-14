<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->staffs() as $staff){
            \App\Models\User::create($staff);            
		}
    }
	
	public function staffs(){		
        yield [
            //'id' => 1,
            'name' => 'Super Admin',
            'gender' => 'male',
            'dob' => '1990-01-01',
            'email' => 'superadmin@prasanthicashews.in',
            'job_type' => 'permanent',
            'address_line_1' => 'Address 1',
            'city' => 'Kollam',
            'district' => 'Kollam',
            'state' => 'Kerala',
            'pincode' => '654321',
            'mobile' => '98765 43210',
            'whatsapp' => '98765 43210',
            'username' => 'super',
            'password' => \Hash::make('Super1234'),
            'status' => 'active',
            'user_group' => 'super-admin',
            'work_location_type' => 'office',
        ];
    }

}