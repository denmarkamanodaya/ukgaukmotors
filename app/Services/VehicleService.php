<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : VehicleService.php
 **/

namespace App\Services;

use App\Models\Postcode;
use App\Models\VehicleBodyType;
use App\Models\VehicleCountLog;
use App\Models\VehicleEngineSize;
use App\Models\VehicleLocation;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\VehicleModelVarient;
use App\Models\Vehicles;
use App\Models\VehiclesMedia;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VehicleService
{

    public function getPaginatedList($type=1)
    {
        if($type == 1)
        {
            return Vehicles::with('media')->where('vehicle_listing_type', $type)->orderBy('auction_date', 'ASC')->paginate(25);
        }

        return Vehicles::with('media')->where('vehicle_listing_type', $type)->orderBy('expire_date', 'ASC')->paginate(25);

    }
    
    public function show($id)
    {
        if (Cache::tags(['Vehicle_detail'])->has('vehicle_'.$id)) {
            return Cache::tags(['Vehicle_detail'])->get('vehicle_'.$id);
        }
        try {
            $vehicle = Vehicles::with('make', 'model', 'variant', 'vehicleType', 'media', 'features', 'location', 'engineSize', 'bodyType')->where('slug', $id)->firstOrFail();
            Cache::tags(['Vehicle_detail'])->forever('vehicle_'.$id, $vehicle);
            return $vehicle;
        } catch (ModelNotFoundException $exception) {
            return false;
        }
        
    }

    public function showAuctioneer($id)
    {
        return Vehicles::where('dealer_id', $id)->orderBy('auction_date', 'ASC')->paginate(25);
    }

    public function getCachedVehiclesPaginate($filters, $area='members')
    {
        //$page = \Input::get('page', 1);
        $pageAdded = false;

        if ($filters->request->has('page')) {
            $page = $filters->request->input('page');
        } else {
            $page = 1;
            $pageAdded = true;
        }
        if($this->is_search($filters))
        {
            $cacheKey = $filters->request->fullUrl();
            if($pageAdded) $cacheKey = $cacheKey.'&page=1';
            if (Cache::tags(['vehicle_search'])->has($cacheKey)) {
                return Cache::tags(['vehicle_search'])->get($cacheKey);
            }
            $vehicles = Vehicles::with('media', 'dealer', 'location')->filter($filters)->orderBy('auction_date', 'ASC')->paginate(30);
            Cache::tags(['vehicle_search'])->put($cacheKey, $vehicles, 15);
            return $vehicles;
        }

        if (Cache::tags(['vehicle_list'])->has($area.'_page_'.$page)) {
            return Cache::tags(['vehicle_list'])->get($area.'_page_'.$page);
        }

        $vehicles = Vehicles::with('media')->orderBy('auction_date', 'ASC')->paginate(30);
        Cache::tags(['vehicle_list'])->forever($area.'_page_'.$page, $vehicles);
        return $vehicles;
    }

    private function is_search($filters)
    {
        if(count($filters->request->query()) == 0) return false;
        $searchTerm = false;
        foreach ($filters->request->query() as $key => $value)
        {
            if($key != 'page') $searchTerm = true;
        }
        return $searchTerm;
    }

    public function buildSearchFilters($filters)
    {
        $search['filters'] = $filters->request->query();
        $search['pagination'] = $search['filters'];
        unset($search['filters']['page']);
        unset($search['pagination']['page']);

        foreach ($search['filters'] as $key => $value)
        {
            if($key == 'listingType')
            {
                $value = str_replace(',', ', ', $value);
            }
            $search['filters'][$key] = ucwords(str_replace('-', ' ', $value));
        }
        return $search;
    }

    public function vehiclesMakeList()
    {
        return Cache::rememberForever('vehicle_make_list', function () {
            return VehicleMake::orderBy('name', 'ASC')->pluck('name', 'slug')->toArray();
        });
    }

    public function vehiclesMakeListCount()
    {
        return Cache::rememberForever('vehicle_make_list_counted', function () {
            $vehicleMakes = $this->vehiclesMakeWithCount();
            $vehicleMakeArray = [];
            foreach ($vehicleMakes as $make)
            {
                if($make->slug == 'unlisted') continue;
                if(isset($make->vehiclesCount->aggregate) && $make->vehiclesCount->aggregate > 0) $vehicleMakeArray[$make->slug] = $make->name;
            }
            return $vehicleMakeArray;
        });
    }

    public function vehiclesMakeWithCount()
    {
        return Cache::rememberForever('vehicle_make_with_Count', function () {
            return VehicleMake::with('vehiclesCount')->orderBy('name', 'ASC')->get();
        });
    }

    public function vehicleCount()
    {
        return Cache::rememberForever('vehicle_count', function () {
            return Vehicles::count();
        });
    }

    public function endingSoon($type=1)
    {
        if (Cache::tags(['vehicle_list'])->has('ending_soon_type_'.$type)) {
            return Cache::tags(['vehicle_list'])->get('ending_soon_type_'.$type);
        }
        $vehicle = Vehicles::with('media')->has('media')->where('vehicle_listing_type', $type)->orderBy('auction_date', 'ASC')->limit(20)->get();
        Cache::tags(['vehicle_list'])->forever('ending_soon_type_'.$type, $vehicle);

        return $vehicle;
    }

    public function latestAdditions($type=1)
    {
        if (Cache::tags(['vehicle_list'])->has('latest_additions_type_'.$type)) {
            return Cache::tags(['vehicle_list'])->get('latest_additions_type_'.$type);
        }
        $vehicle = Vehicles::with('media')->has('media')->where('vehicle_listing_type', $type)->orderBy('created_at', 'DESC')->limit(20)->get();

        Cache::tags(['vehicle_list'])->forever('latest_additions_type_'.$type, $vehicle);
        return $vehicle;
    }

    public function relatedModels($mainVehicle)
    {
        if (Cache::tags(['vehicle_list'])->has('Related_Model_'.$mainVehicle->vehicle_model_id)) {
            $vehicle = Cache::tags(['vehicle_list'])->get('Related_Model_'.$mainVehicle->vehicle_model_id);
        } else {
            $vehicle = Vehicles::with('media')->has('media')->where('vehicle_model_id', $mainVehicle->vehicle_model_id)->orderBy('created_at', 'DESC')->limit(4)->get();
            Cache::tags(['vehicle_list'])->forever('Related_Model_'.$mainVehicle->vehicle_model_id, $vehicle);
        }
        
        $vehicle = $vehicle->reject(function( $veh ) use($mainVehicle) {
            return $veh->id == $mainVehicle->id;
        });

        $vehicle = $vehicle->take(3);
        
        return $vehicle;
    }

    public function relatedByMake($make)
    {
        if (Cache::tags(['vehicle_list'])->has('Related_Model_Make'.$make->id)) {
            $vehicle = Cache::tags(['vehicle_list'])->get('Related_Model_Make'.$make->id);
        } else {
            $vehicle = Vehicles::with('media', 'make', 'model')->has('media')->where('vehicle_make_id', $make->id)->orderBy('created_at', 'DESC')->limit(4)->get();
            Cache::tags(['vehicle_list'])->forever('Related_Model_Make'.$make->id, $vehicle);
        }

        return $vehicle;
    }
    public function relatedByModel($model, $make)
    {
        if (Cache::tags(['vehicle_list'])->has('Related_Model_'.$model->id)) {
            $vehicle = Cache::tags(['vehicle_list'])->get('Related_Model_'.$model->id);
        } else {
            $vehicle = Vehicles::with('media', 'make', 'model')->has('media')->where('vehicle_model_id', $model->id)->orderBy('created_at', 'DESC')->limit(4)->get();
            Cache::tags(['vehicle_list'])->forever('Related_Model_'.$model->id, $vehicle);
        }

        if(count($vehicle) == 0) $vehicle = $this->relatedByMake($make);

        return $vehicle;
    }

    public function userShortlist()
    {
        $user = \Auth::user();
        if (Cache::tags(['vehicle_shortlist'])->has('User_'.$user->id)) {
            return Cache::tags(['vehicle_shortlist'])->get('User_'.$user->id);
        } 
        
        if(!$shortlist = $user->shortlist()->pluck('id')->toArray())
        {
           $shortlist = []; 
        }
        Cache::tags(['vehicle_shortlist'])->forever('User_'.$user->id, $shortlist);
        return $shortlist;
    }

    public function userShortlistAdd($vehicleId)
    {
        $vehicle = $this->show($vehicleId);
        $user = \Auth::user();
        \Auth::user()->shortlist()->attach($vehicle);
        Cache::tags(['vehicle_shortlist'])->forget('User_'.$user->id);
    }

    public function userShortlistRemove($vehicleId)
    {
        $vehicle = $this->show($vehicleId);
        $user = \Auth::user();
        \Auth::user()->shortlist()->detach($vehicle);
        Cache::tags(['vehicle_shortlist'])->forget('User_'.$user->id);
    }

    public function userShortlistToggle($vehicleId)
    {
        $vehicle = $this->show($vehicleId);
        $user = \Auth::user();
        
        if($user->shortlist->contains($vehicle))
        {
            $user->shortlist()->detach($vehicle);
            $message['type'] = 'remove';
            $message['message'] = 'Vehicle removed from shortlist';
        } else {
            $user->shortlist()->attach($vehicle);
            $message['type'] = 'add';
            $message['message'] = 'Vehicle added to shortlist';
        }
        Cache::tags(['vehicle_shortlist'])->forget('User_'.$user->id);
        return $message;
    }

    public function getShortlistedVehicles()
    {
        $user = \Auth::user();
        $vehicles = $user->shortlist()->get();
        return $vehicles;
    }

    public function getAuctionDays()
    {
        return Cache::rememberForever('auctionDays', function () {
            $auctionDays = Vehicles::select(array(
                DB::raw('DATE(`auction_date`) as `date`'),
                DB::raw('COUNT(*)as `count`')
            ))
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->pluck('count', 'date');

            $dayList = [];
            $dayList[0] = 'Date';
            foreach($auctionDays as $aDate => $amount)
            {
                $dayList[$aDate] = Carbon::createFromFormat('Y-m-d', $aDate)->toFormattedDateString() . ' ('.$amount.' Vehicles)';
            }

            return $dayList;
        });
    }

    public function deletefromAuctioneer($auctioneer)
    {
        $public_path = config('main.public_path');
        $vehicles = Vehicles::with('media')->where('dealer_id', $auctioneer)->get();
        foreach ($vehicles as $vehicle)
        {
            foreach ($vehicle->media as $media)
            {
                $media->delete();
            }

            $imageFolder = $public_path.'/images/vehicle/'.$vehicle->id;
            if(file_exists($imageFolder))
            {
                @array_map('unlink', glob("$imageFolder/*.*"));
                @rmdir($imageFolder);
            }
            $vehicle->location->delete();
            $vehicle->delete();
        }
        \Artisan::call('cache:clear');
        \Flash::success('Vehicles have been deleted');
        return;

    }

    public function updateToSlug()
    {
        $vehicles = Vehicles::whereNull('slug')->limit(1000)->get();
        foreach ($vehicles as $vehicle)
        {
            if($vehicle->name == '') $vehicle->name = $vehicle->id;
            $vehicle->save();
        }
    }

    public function VehicleCountHistoryLog()
    {
        $vehicleCount = $this->vehicleCount();
        $todaysCount = VehicleCountLog::where('created_at', '>=', Carbon::today()->toDateString())->first();
        if($todaysCount)
        {
            if($vehicleCount > $todaysCount->total)
            {
                $todaysCount->total = $vehicleCount;
                $todaysCount->save();
            }
        } else {
            VehicleCountLog::create([
               'total' => $vehicleCount
            ]);
        }
    }

    public function getVehicleTypeList()
    {
        $vehicleTypes = VehicleType::orderBy()->pluck();
        return $vehicleTypes;
    }

    public function createClassified($request)
    {
        $expire = Carbon::now()->addDays(14)->toDateTimeString();
        $validMOT = $this->isRealDate($request->mot);

        $vehicle_type = VehicleType::where('slug', $request->vehicleType)->first();
        $vehicle_type = ($vehicle_type) ? $vehicle_type->id : 1;

        $vehicle = Vehicles::create([
            'url' => $request->website,
            'name' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'mileage' => isset($request->mileage) ? $request->mileage : '',
            'colour' => isset($request->colour) ? $request->colour : '',
            'gearbox' => $request->gearbox,
            'fuel' => $request->fuel,
            'vehicle_engine_size_id' => $request->vehicle_engine_size_id,
            'vehicle_make_id' => $this->getMakeId($request->vehicleMake),
            'vehicle_model_id' => $this->getModelId($request->vehicleModel),
            'vehicle_variant_id' => $this->getModelVariantId($request->vehicleVarient),
            'expire_date' => $expire,
            'auction_date' => $expire,
            'status' => 'active',
            'registration' => $request->registration,
            'mot' => $validMOT ? $validMOT : '',
            'vehicle_body_type_id' => $request->vehicle_body_type_id,
            'vehicle_type_id' => $vehicle_type,
            'service_history' => $request->service_history,
            'user_id' => \Auth::user()->id,
            'vehicle_listing_type' => 2,
            'county' => $request->county
        ]);

        if($request->postcode != '')
        {
            if($geo_location = Postcode::postcode($request->postcode)->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }

        $vehicle->location = VehicleLocation::create([
            'vehicle_id' => $vehicle->id,
            'address' => $request->address,
            'address2' => $request->address2,
            'city' => $request->city,
            'county' => $request->county,
            'postcode' => $request->postcode,
            'country' => 'GB',
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'longitude' => isset($longitude) ? $longitude : '',
            'latitude' => isset($latitude) ? $latitude : ''
        ]);

        if(!is_array($request->features)) $request->features = [];
        $vehicle->features()->sync($request->features);

        $this->vehicle_image($request, $vehicle);

        \Artisan::call('cache:clear');
        \Flash::success('Classified vehicle has been created');
        \Activitylogger::log('Admin - Added Classified Vehicle : '.$vehicle->name, $vehicle);
        return $vehicle;
    }

    public function updateClassified($request, $id)
    {
        $vehicle = $this->show($id);
        $expire = Carbon::now()->addDays(14)->toDateTimeString();
        $validMOT = $this->isRealDate($request->mot);

        $vehicle_type = VehicleType::where('slug', $request->vehicleType)->first();
        $vehicle_type = ($vehicle_type) ? $vehicle_type->id : 1;

        $vehicle->url = $request->website;
        $vehicle->name = $request->title;
        $vehicle->description = $request->description;
        $vehicle->price = $request->price;
        $vehicle->mileage = isset($request->mileage) ? $request->mileage : '';
        $vehicle->colour = isset($request->colour) ? $request->colour : '';
        $vehicle->gearbox = $request->gearbox;
        $vehicle->fuel = $request->fuel;
        $vehicle->vehicle_engine_size_id = $request->vehicle_engine_size_id;
        $vehicle->vehicle_make_id = $this->getMakeId($request->vehicleMake);
        $vehicle->vehicle_model_id = $this->getModelId($request->vehicleModel);
        $vehicle->vehicle_variant_id = $this->getModelVariantId($request->vehicleVarient);
        $vehicle->expire_date = $expire;
        $vehicle->auction_date = $expire;
        $vehicle->status = 'active';
        $vehicle->registration = $request->registration;
        $vehicle->mot = $validMOT ? $validMOT : '';
        $vehicle->vehicle_body_type_id = $request->vehicle_body_type_id;
        $vehicle->vehicle_type_id = $vehicle_type;
        $vehicle->service_history = $request->service_history;
        $vehicle->save();

        if($request->postcode != '')
        {
            if($geo_location = Postcode::postcode($request->postcode)->first())
            {
                $longitude  = $geo_location->longitude;
                $latitude   = $geo_location->latitude;
            }
        }

        $vehicle->location->vehicle_id = $vehicle->id;
        $vehicle->location->address = $request->address;
        $vehicle->location->address2 = $request->address2;
        $vehicle->location->city = $request->city;
        $vehicle->location->county = $request->county;
        $vehicle->location->postcode = $request->postcode;
        $vehicle->location->country = 'GB';
        $vehicle->location->phone = $request->phone;
        $vehicle->location->email = $request->email;
        $vehicle->location->website = $request->website;
        $vehicle->location->longitude = isset($longitude) ? $longitude : '';
        $vehicle->location->latitude = isset($latitude) ? $latitude : '';
        $vehicle->location->save();

        if(!is_array($request->features)) $request->features = [];
        $vehicle->features()->sync($request->features);

        \Artisan::call('cache:clear');
        \Flash::success('Classified vehicle has been updated');
        \Activitylogger::log('Admin - Updated Classified Vehicle : '.$vehicle->name, $vehicle);
        return $vehicle;
    }

    public function updateAuction($request, $id)
    {
        $vehicle = $this->show($id);
        $validMOT = $this->isRealDate($request->mot);

        $vehicle->url = $request->website;
        $vehicle->name = $request->title;
        $vehicle->description = $request->description;
        $vehicle->price = $request->price;
        $vehicle->mileage = isset($request->mileage) ? $request->mileage : '';
        $vehicle->colour = isset($request->colour) ? $request->colour : '';
        $vehicle->gearbox = $request->gearbox;
        $vehicle->fuel = $request->fuel;
        $vehicle->vehicle_make_id = $this->getMakeId($request->vehicleMake);
        $vehicle->vehicle_model_id = $this->getModelId($request->vehicleModel);
        $vehicle->vehicle_variant_id = $this->getModelVariantId($request->vehicleVarient);
        $vehicle->registration = $request->registration;
        $vehicle->mot = $validMOT ? $validMOT : '';
        $vehicle->save();


        \Artisan::call('cache:clear');
        \Flash::success('Auction vehicle has been updated');
        \Activitylogger::log('Admin - Updated Auction Vehicle : '.$vehicle->name, $vehicle);
        return $vehicle;
    }

    private function getMakeId($make)
    {
        if(!isset($make)) return 1;
        if(trim($make) == '') return 1;
        if(trim($make) == 'unlisted' || trim($make) == '0') return 1;
        if($make = VehicleMake::where('slug', $make)->first()) return $make->id;
        return 1;
    }

    private function getModelId($model)
    {
        if(!isset($model)) return 1;
        if(trim($model) == '') return 1;
        if(trim($model) == 'unlisted' || trim($model) == '0') return 1;
        if($model = VehicleModel::where('id', $model)->first()) return $model->id;
        return 1;
    }

    private function getTypeId($vehicleType)
    {
        if(!isset($vehicleType)) return 1;
        if(trim($vehicleType) == '') return 1;
        if(trim($vehicleType) == 'unlisted' || trim($vehicleType) == '0') return 1;
        if($vehicleType = VehicleType::where('slug', $vehicleType)->first()) return $vehicleType->id;
        return 1;
    }

    private function getModelVariantId($variant)
    {
        if(!isset($variant)) return 1;
        if(trim($variant) == '') return 1;
        if(trim($variant) == 'unlisted' || trim($variant) == '0') return 1;
        if($variant = VehicleModelVarient::where('id', $variant)->first()) return $variant->id;
        return 1;
    }

    private function isRealDate($date)
    {
        if($date == '') return false;
        if (Carbon::createFromFormat('Y-d-m', $date) !== false) {
            return $date;
        }
        return false;
    }

    private function vehicle_image($request, $vehicle)
    {
        if(!$request->file('image')) return;
        if($request->file('image')->isValid())
        {

            $default_image = ($vehicle->media->count() > 0) ? 0 : 1;

            $path = config('main.public_path').'/images/vehicle/'.$vehicle->id.'/';
            $vehicleImage = $request->file('image')->getClientOriginalName();

            $image = Image::make($request->file('image')->getRealPath());

            File::exists($path) or File::makeDirectory($path);

            //Save new
            $image->save($path. $vehicleImage);
            VehiclesMedia::create([
                'vehicle_id' => $vehicle->id,
                'name' => $vehicleImage,
                'type' => 'image',
                'status' => 'active',
                'default_image' => $default_image
            ]);

        }
        return true;
    }

    public function uploadImages($request, $id)
    {
        $vehicle = $this->show($id);
        $rules = array('image' => 'required|image');
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return false;
        } else {
            //image
            $this->vehicle_image($request, $vehicle);
            $this->clearVehicleCache($vehicle->id);
            return true;
        }
    }

    private function clearVehicleCache($id)
    {
        Cache::tags(['Vehicle_detail'])->flush('vehicle_'.$id);
    }

    public function deleteImage($id, $image)
    {
        $vehicle = $this->show($id);
        $media = VehiclesMedia::where('vehicle_id', $vehicle->id)->where('id', $image)->firstOrFail();
        $path = config('main.public_path').'/images/vehicle/'.$vehicle->id.'/'.$media->name;
        @unlink($path);

        if($media->default_image == 1)
        {
            if($media2 = VehiclesMedia::where('vehicle_id', $vehicle->id)->where('id', '!=', $image)->first())
            {
                $media2->default_image = 1;
                $media2->save();
            }

        }

        $media->delete();
        $this->clearVehicleCache($vehicle->id);
        \Flash::success('Image has been removed');
    }

    public function setDefaultImage($id, $image)
    {
        $vehicle = $this->show($id);
        $media = VehiclesMedia::where('vehicle_id', $vehicle->id)->where('id', $image)->firstOrFail();
        $media->default_image = 1;
        $media->save();
        VehiclesMedia::where('vehicle_id', $vehicle->id)->where('id', '!=', $image)->update(['default_image' => 0]);
        $this->clearVehicleCache($vehicle->id);
        \Flash::success('Default Image has been set');
    }

    public function getvehicleMetaList($vehicle=null)
    {
        $data = [];
        $data['engineSize'] = '';
        $data['bodyType'] = '';
        if($vehicle && $vehicle->vehicleType->slug == 'car')
        {
            $engineSize = VehicleEngineSize::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
            $bodyType = VehicleBodyType::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
        }
        elseif($vehicle && $vehicle->vehicleType->slug == 'commercial')
        {
            $engineSize = VehicleEngineSize::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
            $bodyType = VehicleBodyType::where('vehicle_type_id', 3)->orderBy('position', 'ASC')->get();
        }
        elseif($vehicle && $vehicle->vehicleType->slug == 'motorbike')
        {
            $engineSize = VehicleEngineSize::where('vehicle_type_id', 4)->orderBy('position', 'ASC')->get();
            $bodyType = VehicleBodyType::where('vehicle_type_id', 4)->orderBy('position', 'ASC')->get();
        }
        else {
            $engineSize = VehicleEngineSize::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
            $bodyType = VehicleBodyType::where('vehicle_type_id', 2)->orderBy('position', 'ASC')->get();
        }

        $data['engineSize'] = $engineSize->pluck('size', 'id');
        $data['bodyType'] = $bodyType->pluck('name', 'id');


        return $data;
    }

    public function vehicleLocationList()
    {
        return Cache::rememberForever('vehicle_classified_county', function () {
            return VehicleLocation::where('county', '!=', 'null')->where('county', '!=', '0')->where('county', '!=', '')->groupBy('county')->pluck('county', 'county')->toArray();
        });
    }

    public function updateCounty()
    {
        $vehicles = Vehicles::with('dealer','location')->get();
        foreach ($vehicles as $vehicle)
        {
            if($vehicle->vehicle_listing_type == 1)
            {
                $vehicle->county = $vehicle->dealer->county;
            }
            if($vehicle->vehicle_listing_type == 2)
            {
                $vehicle->county = $vehicle->location->county;
            }
            $vehicle->save();
        }
        \Artisan::call('cache:clear');
    }


    public function swapPrices()
    {
        if($vehicles = Vehicles::where('vehicle_listing_type', 2)->get())
        {
            foreach ($vehicles as $vehicle)
            {
                if($vehicle->price == '0.00' && $vehicle->estimate != '')
                {
                    $vehicle->price = priceToFloat($vehicle->estimate);
                    $vehicle->save();
                }
            }
        }

    }

}