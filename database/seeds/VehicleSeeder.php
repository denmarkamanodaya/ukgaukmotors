<?php

use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\VehicleType::create([
           'name' => 'Unlisted',
            'slug' => 'unlisted',
            'system' => 1
        ]);

        \App\Models\VehicleType::create([
            'name' => 'Car',
            'slug' => 'car',
            'system' => 1
        ]);

        \App\Models\VehicleType::create([
            'name' => 'Commercial',
            'slug' => 'commercial',
            'system' => 1
        ]);

        \App\Models\VehicleType::create([
            'name' => 'Motorbike',
            'slug' => 'motorbike',
            'system' => 1
        ]);

        \App\Models\VehicleMake::create([
            'name' => 'Unlisted',
            'slug' => 'unlisted',
            'system' => 1
        ]);

        \App\Models\VehicleModel::create([
            'name' => 'Unlisted',
            'slug' => 'unlisted',
            'system' => 1,
            'vehicle_make_id' => 1
        ]);
    }
}
