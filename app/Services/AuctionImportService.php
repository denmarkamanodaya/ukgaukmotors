<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : AuctionImportService.php
 **/

namespace App\Services;


use App\Models\Dealers;
use App\Models\DeletedVehicles;
use App\Models\MediaQueue;
use App\Models\VehicleBodyType;
use App\Models\VehicleEngineSize;
use App\Models\VehicleLocation;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\Vehicles;
use App\Models\VehiclesMedia;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Quantum\base\Models\User;

class AuctionImportService
{
    private $auctions;
    private $categories;
    private $currentCategory;
    private $currentCategoryType;
    private $currentAuctioneer;
    private $newAuctioneers = 0;
    private $newClassifiedDealer = 0;
    private $currentCatalogue;
    private $currentlot;
    private $newVehicle = 0;
    private $newClassifiedVehicle = 0;
    private $auctioneer;
    private $imagesImported = 0;
    private $vehicleTypes = null;
    private $engineSize = null;
    private $bodyType = null;

    private $imageUrl = 'http://auctions.gaukmedia.com/images';
    private $imageUrl2;
    private $public_path;

    protected $debug = false;

    /**
     * @var AuctionCache
     */
    private $auctionCache;
    /**
     * @var VehicleTypeService
     */
    private $vehicleTypeService;
    /**
     * @var VehicleToolsService
     */
    private $vehicleToolsService;

    public function __construct(AuctionCache $auctionCache, VehicleTypeService $vehicleTypeService, VehicleToolsService $vehicleToolsService)
    {
        $this->auctionCache = $auctionCache;
        $this->vehicleTypeService = $vehicleTypeService;
        $this->public_path = config('main.public_path');
        $this->imageUrl2 = config('gauk.image_url').'/images';
        $this->vehicleToolsService = $vehicleToolsService;
    }

    public function import()
    {
        $this->getAuctions();
        if($this->auctions)
        {
            $this->parseAuctions();
            $this->auctionCache->cacheClear();
            $this->logImport();
        }

    }

    private function saveAuctions()
    {
        file_put_contents(storage_path('auctionImport.json'),  json_encode($this->auctions));
    }

    private function getSavedAuctions()
    {
        $this->auctions = json_decode(file_get_contents(storage_path('auctionImport.json')), true);
    }

    private function removeSavedAuctions()
    {
        if(file_exists(storage_path('auctionImport.json')))
        {
            @unlink(storage_path('auctionImport.json'));
        }

    }

    private function logImport($type="auctions")
    {
        //$user = (\Auth::check()) ? $user = \Auth::user() : User::where('id', 1)->first();
        if($type == "auctions")
        {
            \Activitylogger::log('Import - Auctioneers : '.$this->newAuctioneers, null);
            \Activitylogger::log('Import - Vehicles : '.$this->newVehicle, null);
            \Activitylogger::log('Import - Classified Dealers : '.$this->newClassifiedDealer, null);
            \Activitylogger::log('Import - Classified Vehicles : '.$this->newClassifiedVehicle, null);
        }
        if($type == "media")
        {
            if($this->imagesImported > 0) \Activitylogger::log('Import - Processed Images : '.$this->imagesImported, null);
        }

    }

    private function getAuctions()
    {
        //load locally cached import
        if($this->debug)
        {
            if(file_exists(storage_path('auctionImport.json')))
            {
                $this->getSavedAuctions();
                return true;
            }
        } else {
            $this->removeSavedAuctions();
        }

        //do import
        $client = new Client();
        $apikey = \Settings::get('gauk_import_api_key');
        $testapi = (\Settings::get('gauk_import_api_status') == 'test') ? '/testtemp' : '';
        $res = $client->get('http://auctions.gaukmedia.com/api/'.$apikey.''.$testapi);
        //$res = $client->get('http://gaukparser2.dev/api/'.$apikey.''.$testapi);
        if($res->getStatusCode() == 200)
        {
            $json = json_decode($res->getBody(), true);
            if($json == 'No New Auctions') return false;
            $this->auctions = $json;
            if($this->debug) $this->saveAuctions();
            return true;
        } else {
            \Log::info('API Input Status code: '.$res->getStatusCode());
        }
        return false;
    }

    private function parseAuctions()
    {
        foreach ($this->auctions as $key => $cat)
        {
            $this->categories = $cat;
            $this->auctionCategories();
        }

    }

    private function auctionCategories()
    {
        foreach ($this->categories as $key => $category)
        {
            $this->currentCategory = $category;
            $this->currentCategoryType = $category['import_type'];
            $this->auctioneers();
        }
    }

    private function auctioneers()
    {
        foreach ($this->currentCategory['auctioneer'] as $key => $auctioneer)
        {
            $this->currentAuctioneer = $auctioneer;
            $this->auctioneer();
        }

    }

    private function auctioneer()
    {
        if($this->currentCategoryType == 'classified'){
            $auctType = 'classified';
        } else {
            $auctType = 'auctioneer';
        }


        if(!$this->auctioneer = Dealers::where('name', $this->currentAuctioneer['name'])->first())
        {
            $this->currentAuctioneer['slug'] = str_slug($this->currentAuctioneer['name']);
            $this->auctioneer = Dealers::create([
                'name' => trim($this->currentAuctioneer['name']),
                'slug' => trim($this->currentAuctioneer['slug']),
                'address' => trim($this->currentAuctioneer['address']),
                'country_id' => 826,
                'postcode' => trim($this->currentAuctioneer['postcode']),
                'town' => trim($this->currentAuctioneer['town']),
                'phone' => trim($this->currentAuctioneer['phone']),
                'email' => trim($this->currentAuctioneer['email']),
                'website' => trim($this->currentAuctioneer['website']),
                'auction_url' => trim($this->currentAuctioneer['auction_url']),
                'online_bidding_url' => trim($this->currentAuctioneer['online_bidding_url']),
                'details' => trim($this->currentAuctioneer['details']),
                'buyers_premium' => trim($this->currentAuctioneer['buyers_premium']),
                'directions' => trim($this->currentAuctioneer['directions']),
                'rail_station' => trim($this->currentAuctioneer['rail_station']),
                'notes' => trim($this->currentAuctioneer['notes']),
                'longitude' => trim($this->currentAuctioneer['longitude']),
                'latitude' => trim($this->currentAuctioneer['latitude']),
                'type' => $auctType,
                'status' => trim($this->currentAuctioneer['status']),
                'county' => trim($this->currentAuctioneer['county']),
                'has_streetview' => trim($this->currentAuctioneer['has_streetview'])
            ]);


            if($this->currentCategoryType == 'classified'){
                $this->newClassifiedDealer ++;
            } else {
                $this->newAuctioneers ++;
            }

        } else {
                $this->currentAuctioneer['slug'] = str_slug($this->currentAuctioneer['name']);
                $this->auctioneer->name = trim($this->currentAuctioneer['name']);
                $this->auctioneer->slug = trim($this->currentAuctioneer['slug']);
                $this->auctioneer->address = trim($this->currentAuctioneer['address']);
                $this->auctioneer->country_id = 826;
                $this->auctioneer->postcode = trim($this->currentAuctioneer['postcode']);
                $this->auctioneer->town = trim($this->currentAuctioneer['town']);
                $this->auctioneer->phone = trim($this->currentAuctioneer['phone']);
                $this->auctioneer->email = trim($this->currentAuctioneer['email']);
                $this->auctioneer->website = trim($this->currentAuctioneer['website']);
                $this->auctioneer->auction_url = trim($this->currentAuctioneer['auction_url']);
                $this->auctioneer->online_bidding_url = trim($this->currentAuctioneer['online_bidding_url']);
                $this->auctioneer->details = trim($this->currentAuctioneer['details']);
                $this->auctioneer->buyers_premium = trim($this->currentAuctioneer['buyers_premium']);
                $this->auctioneer->directions = trim($this->currentAuctioneer['directions']);
                $this->auctioneer->rail_station = trim($this->currentAuctioneer['rail_station']);
                $this->auctioneer->notes = trim($this->currentAuctioneer['notes']);
                $this->auctioneer->longitude = trim($this->currentAuctioneer['longitude']);
                $this->auctioneer->latitude = trim($this->currentAuctioneer['latitude']);
                $this->auctioneer->type = $auctType;
                $this->auctioneer->status = trim($this->currentAuctioneer['status']);
                $this->auctioneer->county = trim($this->currentAuctioneer['county']);
                $this->auctioneer->has_streetview = trim($this->currentAuctioneer['has_streetview']);
                $this->auctioneer->save();
        }

        if($this->currentAuctioneer['logo'] != '') $this->auctioneer_image($this->currentAuctioneer['logo'], 'logo');

        $this->catalogues();

    }

    private function catalogues()
    {
        foreach ($this->currentAuctioneer['catalogues'] as $key => $catalogue)
        {
            $this->currentCatalogue = $catalogue;
            $this->lots();
        }
    }

    private function lots()
    {
        $i = 0;
        foreach ($this->currentCatalogue['lots'] as $key => $lot)
        {
            $i++;
            if($this->debug) if($i < 8)continue;
            $this->currentlot = $lot;
            $this->processLot();
        }
    }

    private function processLot()
    {

        if(!$vehicle = Vehicles::where('url', $this->currentlot['lot_id'])->where('dealer_id', $this->auctioneer->id)->first())
        {
            if($this->debug) echo 'Make : '.$this->currentlot['manufacturer'].' - Model : '.$this->currentlot['model'].PHP_EOL;
            $this->sanitiseLot();
            $makeID = $this->getMakeId();
            if($this->debug) echo 'Make Id :'.$makeID.PHP_EOL;
            $modelID = $this->getModelId($makeID);
            if($this->debug) echo 'Model Id :'.$modelID.PHP_EOL;
            if($this->debug) exit();
            $typeID = $this->getTypeId();
            $engineSize = $this->getEngineSize();
            $bodyType = $this->getBodyType();

            if($this->currentlot['product_name'] == '') $this->currentlot['product_name'] = str_random(20);
            $this->currentlot['product_name'] = preg_replace('/\s+/', ' ', trim($this->currentlot['product_name']));
            $this->currentlot['product_name'] = str_replace(["\r", "\n"], '', $this->currentlot['product_name']);

            if($this->currentCategoryType == 'classified')
            {
                $vehicle_listing_type = 2;
                $vCounty = isset($this->currentlot['county']) ? $this->currentlot['county'] : '';
            } else {
                $vehicle_listing_type = 1;
                $vCounty = isset($this->auctioneer->county) ? $this->auctioneer->county : '';

            }

            if(isset($this->currentlot['product_price']) && $this->currentlot['product_price'] == '')
            {

                if(isset($this->currentlot['price']) && $this->currentlot['price'] != '')
                {
                    $this->currentlot['product_price'] = priceToFloat($this->currentlot['price']);
                }

            }
            $this->currentlot['product_price'] = (double)filter_var($this->currentlot['product_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            if($this->currentlot['product_price'] == '') $this->currentlot['product_price'] = null;

            $gearbox = ['manual','automatic','semi-automatic','unlisted'];
            if(!in_array($this->currentlot['gearbox'], $gearbox)) $this->currentlot['gearbox'] = 'unlisted';

            $fuel = ['petrol','diesel','electric','hybrid','lpg','unlisted'];
            if(!in_array($this->currentlot['fuel'], $fuel)) $this->currentlot['fuel'] = 'unlisted';

           $vehicle = Vehicles::create([
               'dealer_id' => $this->auctioneer->id,
               'url' => $this->currentlot['lot_id'],
               'name' => $this->currentlot['product_name'],
               'description' => $this->currentlot['product_des'],
               'price' => $this->currentlot['product_price'],
               'mileage' => isset($this->currentlot['mileage']) ? $this->currentlot['mileage'] : '',
               'colour' => isset($this->currentlot['colour']) ? $this->currentlot['colour'] : '',
               'gearbox' => isset($this->currentlot['gearbox']) ? $this->currentlot['gearbox'] : '',
               'fuel' => isset($this->currentlot['fuel']) ? $this->currentlot['fuel'] : '',
               'engine_size' => isset($this->currentlot['engine_size']) ? $this->currentlot['engine_size'] : '',
               'vehicle_engine_size_id' => ($engineSize) ? $engineSize->id : null,
               'vehicle_make_id' => $makeID,
               'vehicle_model_id' => $modelID,
               'vehicle_type_id' => $typeID,
               'auction_date' => $this->currentlot['auction_date'],
               'expire_date' => $this->currentlot['auction_date'],
               'status' => 'active',
               'vehicle_listing_type' => $vehicle_listing_type,

               'registration' => isset($this->currentlot['registration']) ? $this->currentlot['registration'] : '',
               'mot' => isset($this->currentlot['mot']) ? $this->currentlot['mot'] : '',
               'estimate' => isset($this->currentlot['estimate']) ? $this->currentlot['estimate'] : '',
               'co2' => isset($this->currentlot['co2']) ? $this->currentlot['co2'] : '',
               'type' => isset($this->currentlot['type']) ? $this->currentlot['type'] : '',
               'vehicle_body_type_id' => ($bodyType) ? $bodyType->id : null,
               'service_history' => isset($this->currentlot['service_history']) ? $this->currentlot['service_history'] : '',
               'additional_info' => isset($this->currentlot['additional_info']) ? $this->currentlot['additional_info'] : '',
               'county' => $vCounty

           ]);

           if($this->currentCategoryType == 'classified')
           {
               $vehicle->location = VehicleLocation::create([
                   'vehicle_id' => $vehicle->id,
                   'address' => isset($this->currentlot['address']) ? $this->currentlot['address'] : '',
                   'address2' => isset($this->currentlot['address2']) ? $this->currentlot['address2'] : '',
                   'city' => isset($this->currentlot['city']) ? ucfirst(strtolower($this->currentlot['city'])) : '',
                   'county' => isset($this->currentlot['county']) ? ucfirst(strtolower($this->currentlot['county'])) : '',
                   'postcode' => isset($this->currentlot['postcode']) ? $this->currentlot['postcode'] : '',
                   'country' => isset($this->currentlot['country']) ? $this->currentlot['country'] : '',
                   'phone' => '',
                   'email' => '',
                   'website' => $vehicle->url,
                   'longitude' => '',
                   'latitude' => ''
               ]);
           }

            if($this->currentCategoryType == 'classified')
            {
                $this->newClassifiedVehicle ++;
            } else {
                $this->newVehicle ++;
            }
        }

        //process images
        $this->queue_media($vehicle, 'image');
        
    }

    private function sanitiseLot()
    {
        foreach ($this->currentlot as $lotKey => $lot)
        {
            if($lotKey == 'images') continue;
            if(!is_array($lot)) $this->currentlot[$lotKey] =  trim(filter_var($lot, FILTER_SANITIZE_STRING));
        }
    }
    
    private function queue_media($vehicle, $type)
    {
        MediaQueue::create([
            'type' => $type,
            'process_media' => $this->currentlot['images'],
            'model_id' => $vehicle->id,
            'model'=> '\\App\\Models\\Vehicles',
            //'remote_path' => $this->imageUrl2.'/lots/'.$this->currentlot['id'],
            'remote_path' => $this->currentlot['id']

        ]);
    }

    private function getMakeId()
    {
        if(!isset($this->currentlot['manufacturer'])) return 1;
        if(trim($this->currentlot['manufacturer']) == '') return 1;
        $manu = strtolower($this->currentlot['manufacturer']);
        //if(!makeAllowed($manu)) return 1;
        //if($make = VehicleMake::where('name', $manu)->first()) return $make->id;
        if($make = $this->vehicleToolsService->getExactMake($manu, true)) return $make->id;
        return 1;
    }

    private function getModelId($makeId)
    {
        if($makeId == 1) return 1;
        if(!isset($this->currentlot['model'])) return 1;
        if($this->currentlot['model'] == '') return 1;
        $vmodel = strtolower(trim($this->currentlot['model']));
        if($model = $this->vehicleToolsService->getExactModel($vmodel, $makeId, true)) return $model->id;

        return 1;
    }

    private function getTypeId()
    {
        if(is_null($this->vehicleTypes)) $this->vehicleTypes = $this->vehicleTypeService->getCachedTypes();

        if(!isset($this->currentlot['vehicle_type']))
        {
            $vtype = $this->vehicleTypes->where('slug', 'car')->all();
           return $vtype[0]->id;
        }

        $vtype = $this->vehicleTypes->where('name', $this->currentlot['vehicle_type'])->all();
        if($vtype) return $vtype[0]->id;
        //unlisted
        $vtype = $this->vehicleTypes->where('slug', 'unlisted')->all();
        return $vtype[0]->id;

    }

    private function getEngineSize()
    {
        if(is_null($this->engineSize)) $this->engineSize = VehicleEngineSize::get();

        if(isset($this->currentlot['engine_size']) && $this->currentlot['engine_size'] != ''){
            if($engineSize = $this->engineSize->where('slug', $this->currentlot['engine_size'])->first()){
                return $engineSize;
            }
        }
        return false;
    }
    private function getBodyType()
    {
        if(is_null($this->bodyType)) $this->bodyType = VehicleBodyType::get();

        if(isset($this->currentlot['type']) && $this->currentlot['type'] != ''){
            if($bodyType = $this->bodyType->where('slug', str_slug($this->currentlot['type']))->first()){
                return $bodyType;
            }
        }
        return false;
    }

    private function copyRemote($fromUrl, $toFile) {
        try {
            $client = new Client();
            $client->request('GET', $fromUrl, ['save_to' => $toFile]);
            return true;
        } catch (\Exception $e) {
            // Log the error or something
            return false;
        }
    }

    private function auctioneer_image($image, $type='image')
    {
        $path = $this->public_path.'/images/dealers/'.$this->auctioneer->id;
        $this->dirCheck($path);
        $image2 = str_replace(' ', '_', $image);
        $toFile = $this->public_path.'/images/dealers/'.$this->auctioneer->id.'/'.$image2;

        if($type == 'logo')
        {
            $remoteImage = $this->imageUrl.'/auctioneer/'.$this->currentAuctioneer['id'].'/'.$image;
        }

        if($type == 'image')
        {
            $remoteImage = $this->imageUrl.'/auctioneer/'.$this->currentAuctioneer['id'].'/'.$image;
        }

        $this->copyRemote($remoteImage, $toFile);

        $img = Image::make($toFile);

        $img->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path.'/thumb300-'.$image2);

        $img->resize(150, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path.'/thumb150-'.$image2);

        $img->resize(100, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path.'/thumb100-'.$image2);

        $img->resize(50, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path.'/thumb50-'.$image2);

        $this->auctioneer->logo = $image2;
        $this->auctioneer->save();
        
    }
    
    public function process_media_queue()
    {
        $mediaQueue = MediaQueue::where('model', '\App\Models\Vehicles')->get();
        
        foreach ($mediaQueue as $media)
        {
            $mediaToProcess = unserialize($media->process_media);
            if (is_array($mediaToProcess)) {
                foreach ($mediaToProcess as $mediaName) {
                    $this->vehicle_image2($media, $mediaName);
                    $this->imagesImported ++;
                }
            }
            $media->delete();
        }
        $this->logImport('media');
        $this->auctionCache->cacheClear();
    }

    private function vehicle_image($media, $image)
    {
        if($image == '') return;

        $savedImage = VehiclesMedia::where('vehicle_id', $media->model_id)->where('name', $image)->where('type', $media->type)->first();

        $path = $this->public_path.'/images/vehicle/'.$media->model_id;
        $this->dirCheck($path);
        $toFile = $this->public_path.'/images/vehicle/'.$media->model_id.'/'.$image;

        if($media->type == 'image')
        {
            $remote_image = $media->remote_path.'/'.$image;
        }

        if($this->copyRemote($remote_image, $toFile))
        {
            if(!$savedImage)
            {
                VehiclesMedia::create([
                    'vehicle_id' => $media->model_id,
                    'name' => $image,
                    'type' => $media->type,
                    'status' => 'active'
                ]);
            }
            $img = Image::make($toFile);
            
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb300-'.$image);

            $img->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb150-'.$image);

            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb100-'.$image);

            $img->resize(50, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path.'/thumb50-'.$image);

        }
    }

    private function vehicle_image2($media, $image)
    {
        if($image == '') return;

        $savedImage = VehiclesMedia::where('vehicle_id', $media->model_id)->where('name', $image)->where('type', $media->type)->first();

        if(!$savedImage)
        {
            //$lot = substr(strrchr($media->remote_path, "/"), 1);

            VehiclesMedia::create([
                'vehicle_id' => $media->model_id,
                'name' => $image,
                'type' => $media->type,
                'status' => 'active',
                'remote_type' => '2',
                'remote_lot' => $media->remote_path
            ]);
        }

    }

    private function dirCheck($path)
    {
        if (!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
    }

    public function removeExpired()
    {
        $expired = 0;
        if($vehicles = Vehicles::with('media')->where('expire_date', '<', Carbon::now()->startOfDay()->toDateTimeString())->get())
        {
            foreach ($vehicles as $vehicle)
            {

                if($vehicle->media && $vehicle->media->count() > 0)
                {
                    $pubpath = false;
                    $media = $vehicle->media->first();
                    //current/old location
                    if($media->remote_type == 1)
                    {
                        $pubpath = config('main.public_path').'/images/vehicle/'.$media->vehicle_id;
                    }
                    //new import location
                    if($media->remote_type == 2)
                    {
                        //do nothing,, delete handled automatically by cdn
                        //$pubpath = env('IMAGE_PATH').'/images/lots/'.$media->remote_lot;

                    }
                    //local moved to new location
                    if($media->remote_type == 3)
                    {
                        //Add to database for cdn to handle
                        //$pubpath = env('IMAGE_PATH').'/images/vehicles/'.$media->vehicle_id;
                        DeletedVehicles::create(['vehicle_id' => $vehicle->id]);
                    }
                    $vehicle->media()->delete();
                    if($pubpath && file_exists($pubpath))
                    {
                        @array_map('unlink', glob("$pubpath/*.*"));
                        @rmdir($pubpath);
                    }
                }

                $vehicle->matchLogs()->delete();
                $vehicle->delete();
                $expired++;
            }
            $this->logExpired($expired);
            $this->auctionCache->cacheClear();
        }
        
    }

    private function logExpired($expired, $type="auctions")
    {
        //$user = (\Auth::check()) ? $user = \Auth::user() : User::where('id', 1)->first();
        if($type == "auctions")
        {
            \Activitylogger::log('Deleted Vehicles : '.$expired, null);
        }

    }

    public function removeOldMedia()
    {
        $mediaAll = VehiclesMedia::all();
        foreach ($mediaAll as $media)
        {
            if(!$vehicle = Vehicles::where('id', $media->vehicle_id)->first()) $media->delete();
        }
    }

    public function getBrokenImages()
    {
        $mediaAll = MediaQueue::orderBy('id', 'ASC')->get();
        foreach ($mediaAll as $media)
        {
            if(!$vehicle = Vehicles::where('id', $media->model_id)->first()) {
                $media->delete();
               continue;
            }
            if($lot = $this->getLotImages($vehicle))
            {
                $media->process_media = $lot->images;
                $media->save();
            } else {
                $media->delete();
            }
            dd('done');
        }
    }

    private function getLotImages($vehicle)
    {
        if($lot = \DB::connection('mysql2')->select('select * from auction_lots where images_processed = ? and lot_id = ?', ['y', $vehicle->url])) return $lot[0];
        return false;
    }

    public function insert_to_vehicle_media()
    {
            $data = Vehicles::where('images', '!=', '')->get();

            foreach($data AS $key => $value)
            {
                $isExist = VehiclesMedia::where('vehicle_id', $value->id)->first();

                if( ! $isExist )
                {
                        VehiclesMedia::create([
                                'vehicle_id' => $value->id,
                                'name' => $value->id . "_" . $value->slug,
                        ]);
                }

	     }     
     }

}
