<?php

use Illuminate\Database\Seeder;

class Vehicle_Body_Type_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(\App\Models\VehicleEngineSize::count() == 0)
        {
            $i = 1;
            foreach(carEngineSizeArray() as $key => $size)
            {
                \App\Models\VehicleEngineSize::create([
                    'vehicle_type_id' => 2,
                    'slug' => $key,
                    'size' => $size,
                    'position' => ($key == 'unlisted')? 1000:$i
                ]);
                $i++;
            }
            $i = 1;
            foreach(bikeEngineSizeArray() as $key => $size)
            {
                \App\Models\VehicleEngineSize::create([
                    'vehicle_type_id' => 4,
                    'slug' => $key,
                    'size' => $size,
                    'position' => ($key == 'unlisted')? 1000:$i
                ]);
                $i++;
            }
            $i = 1;
            foreach(carBodyTypeArray() as $key => $size)
            {
                \App\Models\VehicleBodyType::create([
                    'vehicle_type_id' => 2,
                    'slug' => $key,
                    'name' => $size,
                    'position' => ($key == 'unlisted')? 1000:$i
                ]);
                $i++;
            }
            $i = 1;
            foreach(vanBodyTypeArray() as $key => $size)
            {
                \App\Models\VehicleBodyType::create([
                    'vehicle_type_id' => 3,
                    'slug' => $key,
                    'name' => $size,
                    'position' => ($key == 'unlisted')? 1000:$i
                ]);
                $i++;
            }
            $i = 1;
            foreach(bikeBodyTypeArray() as $key => $size)
            {
                \App\Models\VehicleBodyType::create([
                    'vehicle_type_id' => 4,
                    'slug' => $key,
                    'name' => $size,
                    'position' => ($key == 'unlisted')? 1000:$i
                ]);
                $i++;
            }
        }

    }
}
