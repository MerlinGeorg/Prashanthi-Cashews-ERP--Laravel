<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->offices() as $office){
            \App\Models\Office::create($office);            
		}
    }
	
	public function offices(){		
        yield [
            //'id' => 1,
            'office_name' => 'Head Office',
            'office_short_name' => 'HO',
            'office_reg_number' => 'PRK001',
            'office_location' => 'Location',
            'office_address_1' => 'Address 1',
            'office_address_2' => 'Address 2',
            'office_state' => 'Kerala',
            'office_pincode' => '654321',
            'office_phone_number' => '98765 43210',
            'office_type' => 'HO',
        ];
    }

}