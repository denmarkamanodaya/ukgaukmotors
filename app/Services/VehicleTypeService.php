<?php

namespace App\Services;


use App\Models\VehicleMake;
use App\Models\VehicleMakeDescription;
use App\Models\VehicleModel;
use App\Models\VehicleModelDescription;
use App\Models\VehicleModelVariantDescription;
use App\Models\VehicleModelVarient;
use App\Models\Vehicles;
use App\Models\VehicleType;
use Illuminate\Support\Facades\Cache;
use Laracasts\Flash\Flash;
use League\Csv\Reader;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Quantum\blog\Models\Posts;

class VehicleTypeService
{

    /**
     * @var AuctionCache
     */
    private $auctionCache;

    public function __construct(AuctionCache $auctionCache)
    {
        $this->auctionCache = $auctionCache;
    }

    public function createType($request)
    {
     $vehicleType = VehicleType::create([
         'name' => $request->name,
         'slug' => str_slug($request->name),
         'system' => 0
     ]);
        Flash::success('Vehicle Type has been created.');
        \Activitylogger::log('Admin - Created Vehicle Type : '.$vehicleType->name, $vehicleType);
        $this->clearCache();
        return $vehicleType;
    }
    
    public function editType($request, $id)
    {
        $vehicleType = VehicleType::where('slug', $id)->where('system', '0')->firstOrFail();
        $vehicleType->name = $request->name;
        $vehicleType->slug = str_slug($request->name);
        $vehicleType->save();
        Flash::success('Vehicle Type has been updated.');
        \Activitylogger::log('Admin - Updated Vehicle Type : '.$vehicleType->name, $vehicleType);
        $this->clearCache();
        return $vehicleType;
    }
    
    public function deleteType($id)
    {
        $vehicleType = VehicleType::where('slug', $id)->where('system', '0')->firstOrFail();
        $vehicleType->delete();
        Flash::success('Vehicle Type has been deleted.');
        \Activitylogger::log('Admin - Deleted Vehicle Type : '.$vehicleType->name, $vehicleType);
        $this->clearCache();
        return $vehicleType;
    }

    public function getCachedMakesFull()
    {
        $vehicleMakes = \Cache::rememberForever('vehicleMakes', function() {
            return VehicleMake::with('description', 'country', 'models')->orderBy('name', 'ASC')->get();
        });
        return $vehicleMakes;
    }
    
    public function getCachedTypes()
    {
        $vehicleTypes = Cache::rememberForever('vehicletypes', function() {
            return \App\Models\VehicleType::orderBy('name', 'ASC')->get();
        });
        return $vehicleTypes;
    }

    public function getCachedList()
    {
        $vehicleTypes = $this->getCachedTypes();
        $vehicleTypes = $vehicleTypes->pluck('name', 'slug')->toArray();
        return $vehicleTypes;
    }

    public function clearCache()
    {
        Cache::forget('vehicletypes');
        Cache::forget('vehicleMakes');
        Cache::forget('vehicle_make_list');
        Cache::forget('vehicle_make_with_Count');
        Cache::tags('Vehicle_Model_Variant')->flush();
        Cache::tags('Vehicle_Model_Detail')->flush();
        Cache::forget('vehicle_makes_full');
    }

    public function editMake($request, $id)
    {
        $vehicleMake = VehicleMake::where('slug', $id)->firstOrFail();
        if($vehicleMake->system == '0')
        {
            $vehicleMake->name = ucwords(strtolower($request->name));
            $vehicleMake->slug = str_slug($request->name);
        }
        $vehicleMake->logo = $this->logoPicture($vehicleMake, $request);
        $vehicleMake->country_id = $request->country_id;

        $vehicleMake->save();
        Flash::success('Vehicle Make has been updated.');
        \Activitylogger::log('Admin - Updated Vehicle Make : '.$vehicleMake->name, $vehicleMake);
        $this->clearCache();
        return $vehicleMake;
    }

    public function deleteMake($id)
    {
        $vehicleMake = VehicleMake::with('models')->where('slug', $id)->where('system', '0')->firstOrFail();
        if(count($vehicleMake->models) > 0)
        {
            Flash::error('Vehicle has assigned models. Please remove them first.');
            return $vehicleMake;
        }

        VehicleMakeDescription::where('vehicle_make_id', $vehicleMake->id)->delete();
        Vehicles::where('vehicle_make_id', $vehicleMake->id)->update(['vehicle_make_id' => 1, 'vehicle_model_id' => 1]);
        $vehicleMake->delete();
        Flash::success('Vehicle Make has been deleted.');
        \Activitylogger::log('Admin - Deleted Vehicle Make : '.$vehicleMake->name, $vehicleMake);
        $this->clearCache();
        $this->auctionCache->cacheClear();
        return $vehicleMake;
    }

    public function removeLogoPicture($id)
    {
        $vehicleMake = VehicleMake::where('slug', $id)->firstOrFail();

        $path = vehicle_make_logo_path($vehicleMake->id);
        $this->deleteLogoImages($path, $vehicleMake);

        $vehicleMake->logo = '';
        $vehicleMake->save();

        Flash::success('Logo has been removed.');
        return $vehicleMake;
    }

    private function logoPicture($vehicleMake, $request)
    {

        $logo = isset($vehicleMake->logo)? $vehicleMake->logo : '';
        $path = vehicle_make_logo_path($vehicleMake->id);
        if($request['delPicture'])
        {
            $this->deleteLogoImages($path, $vehicleMake);
            $logo = '';
        }

        if($request->file('logo'))
        {

            $logo = $request->file('logo')->getClientOriginalName();

            $image = Image::make($request->file('logo')->getRealPath());

            File::exists($path) or File::makeDirectory($path);

            if($logo != '')
            {
                $this->deleteLogoImages($path, $vehicleMake);
            }

            //Save new
            $image->save($path. $logo);
            $image->resize(300, 300)->save($path.'thumb300-'.$logo);
            $image->resize(200, 200)->save($path.'thumb200-'.$logo);
            $image->resize(100, 100)->save($path.'thumb100-'.$logo);

            $image->resize(50, 50)->save($path.'thumb50-'.$logo);
            $image->resize(40, 40)->save($path.'thumb40-'.$logo);
            $image->resize(30, 30)->save($path.'thumb30-'.$logo);
            $image->resize(20, 20)->save($path.'thumb20-'.$logo);

        }
        return $logo;
    }

    private function deleteLogoImages($path, $vehicleMake)
    {
        //remove old images
        File::delete($path . $vehicleMake->logo);
        File::delete($path . 'thumb300-' . $vehicleMake->logo);
        File::delete($path . 'thumb200-' . $vehicleMake->logo);
        File::delete($path . 'thumb100-' . $vehicleMake->logo);
        File::delete($path . 'thumb50-' . $vehicleMake->logo);
        File::delete($path . 'thumb40-' . $vehicleMake->logo);
        File::delete($path . 'thumb30-' . $vehicleMake->logo);
        File::delete($path . 'thumb20-' . $vehicleMake->logo);
    }

    public function importCsv()
    {

        $csv = app_path('Import/import_vehicle_data.csv');
        if(!file_exists($csv)) return false;
        $reader = Reader::createFromPath($csv);
        //$data = $reader->fetchOne(1);
        //$this->process_import_item($data);
        $reader->fetchAll();
        foreach($reader as $data)
        {
            $this->process_import_item($data);
        }

        $this->convertVehicles();
        $this->cleanup();
        $this->clearCache();
        $this->auctionCache->cacheClear();
    }

    public function convertVehicles()
    {
        $vehicles = Vehicles::all();

        foreach ($vehicles as $vehicle)
        {
            if($vehicle->vehicle_make_id == '1') continue;
            if($vmake = VehicleMake::where('id', $vehicle->vehicle_make_id)->first())
            {
                $makename = ucwords(strtolower($vmake->name));
                if($new_vmake = VehicleMake::where('name', $makename)->where('system', '1')->first())
                {
                    $new_vmake_id = $new_vmake->id;
                } else {
                    $new_vmake_id = 1;
                }
            } else {
                $new_vmake_id = 1;
            }



            if($vmodel = VehicleModel::where('id', $vehicle->vehicle_model_id)->first())
            {
                $modelname = ucwords(strtolower($vmodel->name));
                if($new_vmodel = VehicleModel::where('vehicle_make_id', $new_vmake_id)->where('name', $modelname)->where('system', '1')->first())
                {
                    $new_vmodel_id = $new_vmodel->id;
                } else {
                    $new_vmodel_id = 1;
                }
            } else {
                $new_vmodel_id = 1;
            }

//dd($vmake, $new_vmake, $vmodel, $new_vmodel);
            $vehicle->vehicle_make_id = $new_vmake_id;
            $vehicle->vehicle_model_id = $new_vmodel_id;
            $vehicle->save();
        }
    }

    private function cleanup()
    {
        VehicleMake::where('id', '!=', 1)->where('system', '0')->delete();
        VehicleModel::where('id', '!=', 1)->where('system', '0')->delete();
    }

    private function process_import_item($data)
    {
        $vmake = ucwords(strtolower($data[0]));
        $vehicleMake = VehicleMake::where('name', $vmake)->where('system', '1')->first();
        if(!$vehicleMake)
        {
            $vehicleMake = VehicleMake::create([
                'name' => $vmake,
                'slug' => str_slug($data[0]),
                'logo' => '',
                'system' => 1
            ]);
        }

        $vmodel = ucwords(strtolower($data[1]));
        $vehicleModel = VehicleModel::where('vehicle_make_id', $vehicleMake->id)->where('name', $vmodel)->where('system', '1')->first();
        if(!$vehicleModel)
        {
            $vehicleModel = VehicleModel::create([
                'vehicle_make_id' => $vehicleMake->id,
                'name' => $vmodel,
                'slug' => str_slug($vmodel),
                'system' => 1
            ]);
        }

        $model_desc = htmlspecialchars($data[4], ENT_COMPAT,'ISO-8859-1', true);
        $model_desc = htmlentities($model_desc, ENT_COMPAT,'ISO-8859-1', true);
        //dd($model_desc);

        $vehicleVarient = VehicleModelVarient::where('vehicle_model_id', $vehicleModel->id)->where('model_desc', $model_desc)->first();
        if(!$vehicleVarient)
        {
            $vehicleVarient = VehicleModelVarient::create([
                'vehicle_model_id' => $vehicleModel->id,
                'model_platform' => isset($data[2]) ? $data[2] : '',
                'model_name' => isset($data[3]) ? $data[3]: '',
                'model_desc' => isset($data[4]) ? $model_desc : '',
                'source' => isset($data[5]) ? $data[5]: '',
                'year_sold' => isset($data[6]) ? $data[6] : '',
                'location' => isset($data[7]) ? $data[7] : '',
                'classification' => isset($data[8]) ? $data[8] : '',
                'body_type' => isset($data[9]) ? $data[9] : '',
                'doors' => isset($data[10]) ? $data[10] : '',
                'seats' => isset($data[11]) ? $data[11] : '',
                'engine_place' => isset($data[12]) ? $data[12] : '',
                'drivetrain' => isset($data[13]) ? $data[13] : '',
                'cylinders' => isset($data[14]) ? $data[14] : '',
                'displacement' => isset($data[15]) ? $data[15] : '',
                'power_ps' => isset($data[16]) ? $data[16] : '',
                'power_kw' => isset($data[17]) ? $data[17] : '',
                'power_rpm' => isset($data[18]) ? $data[18] : '',
                'torque_nm' => isset($data[19]) ? $data[19] : '',
                'torque_rpm' => isset($data[20]) ? $data[20] : '',
                'bore_stroke' => isset($data[21]) ? $data[21] : '',
                'compression_ration' => isset($data[22]) ? $data[22] : '',
                'valves_cylinder' => isset($data[23]) ? $data[23] : '',
                'crankshaft' => isset($data[24]) ? $data[24] : '',
                'fuel_injection' => isset($data[25]) ? $data[25] : '',
                'supercharged' => isset($data[26]) ? $data[26] : '',
                'catalytic' => isset($data[27]) ? $data[27] : '',
                'manual' => isset($data[28]) ? $data[28] : '',
                'automatic' => isset($data[29]) ? $data[29] : '',
                'suspension_front' => isset($data[30]) ? $data[30] : '',
                'suspension_rear' => isset($data[31]) ? $data[31] : '',
                'assisted_steering' => isset($data[32]) ? $data[32] : '',
                'brakes_front' => isset($data[33]) ? $data[33] : '',
                'brakes_rear' => isset($data[34]) ? $data[34] : '',
                'abs' => isset($data[35]) ? $data[35] : '',
                'esp' => isset($data[36]) ? $data[36] : '',
                'tire_size' => isset($data[37]) ? $data[37] : '',
                'tire_size_rear' => isset($data[38]) ? $data[38] : '',
                'wheel_base' => isset($data[39]) ? $data[39] : '',
                'track_front' => isset($data[40]) ? $data[40] : '',
                'track_rear' => isset($data[41]) ? $data[41] : '',
                'length' => isset($data[42]) ? $data[42] : '',
                'width' => isset($data[43]) ? $data[43] : '',
                'height' => isset($data[44]) ? $data[44] : '',
                'curb_weight' => isset($data[45]) ? $data[45] : '',
                'gross_weight' => isset($data[46]) ? $data[46] : '',
                'cargo_space' => isset($data[47]) ? $data[47] : '',
                'tow_weight' => isset($data[48]) ? $data[48] : '',
                'gas_tank' => isset($data[49]) ? $data[49] : '',
                'zero_hundred' => isset($data[50]) ? $data[50] : '',
                'max_speed' => isset($data[51]) ? $data[51] : '',
                'fuel_eff' => isset($data[52]) ? $data[52] : '',
                'engine_type' => isset($data[53]) ? $data[53] : '',
                'fuel_type' => isset($data[54]) ? $data[54] : '',
                'co2' => isset($data[55]) ? $data[55] : ''
            ]);
        }
    }

    public function vehicleMakeDescription($request, $id)
    {
        $vehicleMake = \App\Models\VehicleMake::with('description')->where('slug', $id)->firstOrFail();
        if(isset($request->featured_image_remove))
        {
            $featured_image = null;
        } else {
            $featured_image = $this->featureImageUpload($request);
            if(!$featured_image)
            {
                if($vehicleMake->description) $featured_image = $vehicleMake->description->featured_image;
            }
        }

        if($vehicleMake->description)
        {
            $vehicleMake->description->content = $request->content;
            $vehicleMake->description->featured_image = $featured_image;
            $vehicleMake->description->save();
        } else {
            \App\Models\VehicleMakeDescription::create([
               'vehicle_make_id' => $vehicleMake->id,
               'featured_image' => $featured_image,
                'content' => $request->content
            ]);
        }
        Cache::forget('vehicleMakes');
        Cache::tags('Vehicle_Make_Detail')->flush();
        Flash::success('Description content has been saved.');
        return $vehicleMake;
    }

    public function vehicleModelDescription($request, $id)
    {
        $vehicleModel = \App\Models\VehicleModel::with('make','description')->where('id', $id)->firstOrFail();
        if(isset($request->featured_image_remove))
        {
            $featured_image = null;
        } else {
            $featured_image = $this->featureImageUpload($request);
            if(!$featured_image)
            {
                if($vehicleModel->description) $featured_image = $vehicleModel->description->featured_image;
            }
        }
        if($vehicleModel->description)
        {
            $vehicleModel->description->content = $request->content;
            $vehicleModel->description->featured_image = $featured_image;
            $vehicleModel->description->save();
        } else {
            \App\Models\VehicleModelDescription::create([
                'vehicle_model_id' => $vehicleModel->id,
                'featured_image' => $featured_image,
                'content' => $request->content
            ]);
        }
        Cache::forget($vehicleModel->make->slug.'-vehiclemodelsearch');
        Cache::tags('Vehicle_Model_Detail')->flush();
        $this->clearCache();
        $this->auctionCache->cacheClear();
        Flash::success('Description content has been saved.');
        return $vehicleModel;
    }

    public function vehicleModelVariantDescription($request, $id)
    {
        $vehicleModelVariant = \App\Models\VehicleModelVarient::with('vehiclemodel', 'description')->where('id', $id)->firstOrFail();
        if(isset($request->featured_image_remove))
        {
            $featured_image = null;
        } else {
            $featured_image = $this->featureImageUpload($request);
            if(!$featured_image)
            {
                if($vehicleModelVariant->description) $featured_image = $vehicleModelVariant->description->featured_image;
            }
        }
        if($vehicleModelVariant->description)
        {
            $vehicleModelVariant->description->content = $request->content;
            $vehicleModelVariant->description->featured_image = $featured_image;
            $vehicleModelVariant->description->save();
        } else {
            \App\Models\VehicleModelVariantDescription::create([
                'vehicle_model_variant_id' => $vehicleModelVariant->id,
                'featured_image' => $featured_image,
                'content' => $request->content
            ]);
        }
        Cache::forget($vehicleModelVariant->vehiclemodel->id.'-vehiclevariantsearch');
        Cache::tags('Vehicle_Model_Variant')->flush();
        Flash::success('Description content has been saved.');
        return $vehicleModelVariant;
    }

    public function convertCarMake($request, $id)
    {
        $post = Posts::with('meta')->where('slug', $id)->firstOrFail();
        $carMake = VehicleMake::with('description')->where('slug', $request->carMake)->firstOrFail();
        if($carMake->description)
        {
            $carMake->description->content = $post->content;
            $carMake->description->featured_image = $post->meta->featured_image;
            $carMake->description->save();
        } else {
            VehicleMakeDescription::create([
                'vehicle_make_id' => $carMake->id,
                'featured_image' => $post->meta->featured_image,
                'content' => $post->content
            ]);
        }
        Flash::success('Post has been converted to Car Make Description.');
        $this->clearCache();
        $this->auctionCache->cacheClear();
        Cache::forget('post_list');
        \Activitylogger::log('Admin - Converted Post ( '.$post->title.' ) to Vehicle Make Description : '.$carMake->name, $carMake);

        $post->revisions()->delete();
        $post->meta()->delete();
        $post->delete();

        return;
    }

    public function convertCarMakeNew($id)
    {
        $post = Posts::with('meta')->where('slug', $id)->firstOrFail();
        if(!$carMake = $this->createMakeConverted($post->title))
        {
            Flash::error('A car manufacturer already exists with this name!');
            return false;
        }

        VehicleMakeDescription::create([
            'vehicle_make_id' => $carMake->id,
            'featured_image' => $post->meta->featured_image,
            'content' => $post->content
        ]);

        Flash::success('Post has been converted to New Car Make and Description.');
        $this->clearCache();
        $this->auctionCache->cacheClear();
        Cache::forget('post_list');
        \Activitylogger::log('Admin - Converted Post ( '.$post->title.' ) to a New Vehicle Make and Description : '.$carMake->name, $carMake);

        $post->revisions()->delete();
        $post->meta()->delete();
        $post->delete();

        return true;

    }

    public function convertCarModel($request, $id)
    {
        $post = Posts::with('meta')->where('slug', $id)->firstOrFail();
        $carModel = VehicleModel::with('description')->where('id', $request->carModel)->firstOrFail();
        if($carModel->description)
        {
            $carModel->description->content = $post->content;
            $carModel->description->featured_image = $post->meta->featured_image;
            $carModel->description->save();
        } else {
            VehicleModelDescription::create([
                'vehicle_model_id' => $carModel->id,
                'featured_image' => $post->meta->featured_image,
                'content' => $post->content
            ]);
        }
        Flash::success('Post has been converted to Car Model Description.');
        $this->clearCache();
        $this->auctionCache->cacheClear();
        Cache::forget('post_list');
        \Activitylogger::log('Admin - Converted Post ( '.$post->title.' ) to Vehicle Make Description : '.$carModel->name, $carModel);

        $post->revisions()->delete();
        $post->meta()->delete();
        $post->delete();

        return;
    }

    private function featureImageUpload($request)
    {
        $user = \Auth::user();
        $path = config('main.public_path').'/photos/'.$user->username;
        File::exists($path) or File::makeDirectory($path);
        File::exists($path.'/thumbs') or File::makeDirectory($path.'/thumbs');

        if($request->file('featured_image'))
        {
            $featured_image = $request->file('featured_image')->getClientOriginalName();
            $image = Image::make($request->file('featured_image')->getRealPath());
            //Save new
            $featured_image = str_replace(' ', '_', $featured_image);
            $image->save($path.'/'.$featured_image);
            $image->resize(200, 200)->save($path.'/thumbs/'.$featured_image);
            return $user->username.'/'.$featured_image;
        }
        return false;
    }


    private function createMakeConverted($makeName)
    {
        $makeName = ucwords(strtolower($makeName));
        $makeSlug = str_slug($makeName);
        $carMake = VehicleMake::with('description')->where('slug', $makeSlug)->first();
        if($carMake) return false;

        $carMake = VehicleMake::create([
            'name' => $makeName,
            'slug' => $makeSlug,
            'system' => 0
        ]);
        $carMake->load('description');
        return $carMake;
    }

    public function convertCarModelNew($request, $id)
    {
        $post = Posts::with('meta')->where('slug', $id)->firstOrFail();
        $carMake = VehicleMake::with('description')->where('slug', $request->carMake)->first();
        if(!$carMake)
        {
            Flash::error('A car manufacturer does not exists with this name!');
            return false;
        }
        $carModel = VehicleModel::with('description')->where('name', $post->title)->where('vehicle_make_id', $carMake->id)->first();
        if($carModel)
        {
            Flash::error('A car model already exists with this name!');
            return false;
        }

        $modelName = ucwords(strtolower($post->title));
        $carModel = VehicleModel::create([
            'name' => $modelName,
            'slug' => str_slug($modelName),
            'vehicle_make_id' => $carMake->id,
            'system' => 0
        ]);


        VehicleModelDescription::create([
            'vehicle_model_id' => $carModel->id,
            'featured_image' => $post->meta->featured_image,
            'content' => $post->content
        ]);

        Flash::success('Post has been converted to New Car Model and Description.');
        $this->clearCache();
        $this->auctionCache->cacheClear();
        Cache::forget('post_list');
        \Activitylogger::log('Admin - Converted Post ( '.$post->title.' ) to a New Vehicle Model and Description : '.$carModel->name, $carModel);

        $post->revisions()->delete();
        $post->meta()->delete();
        $post->delete();

        return true;

    }

    public function deleteModel($id)
    {
        $vehicleModel = VehicleModel::with('variants', 'make')->where('id', $id)->where('system', '0')->firstOrFail();
        foreach ($vehicleModel->variants as $variant)
        {
            VehicleModelVariantDescription::where('vehicle_model_variant_id', $variant->id)->delete();
            $variant->delete();
        }
        VehicleModelDescription::where('vehicle_model_id', $vehicleModel->id)->delete();
        Vehicles::where('vehicle_model_id', $vehicleModel->id)->update(['vehicle_model_id' => 1]);
        $vehicleModel->delete();
        $this->clearCache();
        $this->auctionCache->cacheClear();

        \Cache::forget($vehicleModel->make->slug.'-vehiclemodelsearch');
        \Activitylogger::log('Admin - Deleted Vehicle Model :'.$vehicleModel->name, $vehicleModel);
        Flash::success('The Vehicle Model has been deleted.');
        return $vehicleModel;
    }

    public function modeltomakeConvert($request)
    {
        $vehicleModel = VehicleModel::with('description','variants', 'make')->where('id', $request->carModel)->where('system', '0')->first();
        if(!$vehicleModel)
        {
            Flash::error('The selected model can not be converted.');
            return;
        }

        $vehicleMake = VehicleMake::where('name', $vehicleModel->name)->first();
        if($vehicleMake)
        {
            Flash::error('A Vehicle Make already exists with the selected name.');
            return;
        }

        $vehicleMake = VehicleMake::create([
            'name' => $vehicleModel->name,
            'slug' => $vehicleModel->slug,
            'system' => 0
        ]);

        \App\Models\VehicleMakeDescription::create([
            'vehicle_make_id' => $vehicleMake->id,
            'featured_image' => $vehicleModel->description->featured_image,
            'content' => $vehicleModel->description->content
        ]);

        foreach($vehicleModel->variants as $variant)
        {
            $variant->delete();
        }
        $vehicleModel->description->delete();
        $vehicleModel->delete();
        $this->clearCache();
        $this->auctionCache->cacheClear();


        \Activitylogger::log('Admin - Converted Vehicle Model to a New Vehicle Make and Description : '.$vehicleMake->name, $vehicleMake);
        Flash::success('The model has been converted');
        return;
    }

    public function editModel($id)
    {
        //$vehicleModel = $vehicleModel = VehicleModel::with('description','variants', 'make')->where('id', $id)->where('system', '0')->firstOrFail();
        $vehicleModel = $vehicleModel = VehicleModel::with('description','variants', 'make')->where('id', $id)->firstOrFail();
        return $vehicleModel;
    }

    public function editModelUpdate($request, $id)
    {
        //$vehicleModel = VehicleModel::with('description','variants', 'make')->where('id', $id)->where('system', '0')->firstOrFail();
        $vehicleModel = VehicleModel::with('description','variants', 'make')->where('id', $id)->firstOrFail();

        if($vehicleModelExists = VehicleModel::where('name', $request->name)->where('id', '!=', $vehicleModel->id)->first())
        {
            Flash::error('A Model with that name already exists');
            return false;
        }

        $vehicleModel->name = $request->name;
        $vehicleModel->slug = str_slug($request->name);
        $vehicleModel->save();
        Flash::success('The Model has been updated');
        $this->clearCache();
        $this->auctionCache->cacheClear();
        Cache::forget($vehicleModel->make->slug.'-vehiclemodelsearch');
        return $vehicleModel;
    }

    public function createMake($request)
    {
        $makeName = ucwords(strtolower($request->name));
        $makeSlug = str_slug($request->name);
        $carMake = VehicleMake::with('description')->where('slug', $makeSlug)->first();
        if($carMake) return false;

        $carMake = VehicleMake::create([
            'name' => $makeName,
            'slug' => $makeSlug,
            'logo' => '',
            'system' => 0,
            'country_id' => $request->country_id
        ]);
        $carMake->logo = $this->logoPicture($carMake, $request);
        $carMake->save();
        $featured_image = $this->featureImageUpload($request);
        \App\Models\VehicleMakeDescription::create([
            'vehicle_make_id' => $carMake->id,
            'featured_image' => $featured_image,
            'content' => $request->content
        ]);
        \Activitylogger::log('Admin - Created vehicle make : '.$carMake->name, $carMake);
        Flash::success('Vehicle Make has been created');
        $this->clearCache();
        $this->auctionCache->cacheClear();
        return $carMake;
    }

    public function createModel($request, $carMake)
    {
        $modelName = ucwords(strtolower($request->name));
        $carModel = VehicleModel::create([
            'name' => $modelName,
            'slug' => str_slug($modelName),
            'vehicle_make_id' => $carMake->id,
            'system' => 0
        ]);

        $featured_image = $this->featureImageUpload($request);
        VehicleModelDescription::create([
            'vehicle_model_id' => $carModel->id,
            'featured_image' => $featured_image,
            'content' => $request->content
        ]);

        Flash::success('Vehicle Model has been created.');
        $this->clearCache();
        $this->auctionCache->cacheClear();
        Cache::forget($carMake->slug.'-vehiclemodelsearch');
        Cache::forget('post_list');
        \Activitylogger::log('Admin - Created vehicle model : '.$carModel->name, $carModel);
        return $carModel;
    }


}