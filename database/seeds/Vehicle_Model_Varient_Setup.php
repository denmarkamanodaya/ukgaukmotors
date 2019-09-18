<?php

use App\Models\VehicleModelVarient;
use Illuminate\Database\Seeder;

class Vehicle_Model_Varient_Setup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VehicleModelVarient::create([
            'vehicle_model_id' => 1,
            'model_platform' => 'Unknown',
            'model_name' => 'Unknown',
            'model_desc' => 'Unknown',
            'system' => 1
        ]);
    }
}
