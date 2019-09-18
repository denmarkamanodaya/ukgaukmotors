<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : ImportingService.php
 **/

namespace App\Services;


use App\Models\DealerCategories;
use App\Models\Dealers;
use App\Models\DealersFeatures;
use App\Models\MediaQueue;
use App\Models\ParsedDealers;
use App\Models\VehicleBodyType;
use App\Models\VehicleEngineSize;
use App\Models\VehicleLocation;
use App\Models\Vehicles;

class ImportingService
{
    //protected $importUrl = "http://gaukparser.test/api2";
    protected $importUrl = "http://auctions.gaukmedia.com/api2";

    protected $apikey;

    private $currentlot;

    private $auctioneer;

    private $currentCategoryType;


    private $categories;

    private $newVehicle = 0;
    private $newClassifiedVehicle = 0;

    private $imagesImported = 0;
    private $vehicleTypes = null;
    private $engineSize = null;
    private $bodyType = null;


    protected $debug = false;
    /**
     * @var VehicleToolsService
     */
    private $vehicleToolsService;
    /**
     * @var VehicleTypeService
     */
    private $vehicleTypeService;

    public function __construct(VehicleToolsService $vehicleToolsService, VehicleTypeService $vehicleTypeService)
    {
        $this->apikey = "923a69bdd94a90e3ade2ee1c7c95f171";
        //$this->apikey = \Settings::get('gauk_import_api_key');
        $this->vehicleToolsService = $vehicleToolsService;
        $this->vehicleTypeService = $vehicleTypeService;
    }

    private function getResources($action, $attr1=null)
    {


        $client = new \GuzzleHttp\Client();
        $apikey = $this->apikey;
        $res = $client->get('http://auctions.gaukmedia.com/api2/'.$apikey.'/'.$action.'/'.$attr1);

        if($res->getStatusCode() == 200)
        {
            $json = json_decode($res->getBody(), true);
            if($json == 'No New '.$action) return false;
            $resources = $json;
            return $resources;
        } else {
            \Log::info('API Input Status code: '.$res->getStatusCode());
        }
        return false;


        /*if(!$action) return null;
        $client = new \GuzzleHttp\Client();

        $attribute1 = "attribute1";
        if($action = 'getLots'){
            $attribute1 = 'slug';
        }

        $response = $client->request('POST', $this->importUrl, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'form_params' => [
                'bearer' => $this->apikey,
                'action' => $action,
                $attribute1 => $attr1
            ]

        ]);
        //return $res->getStatusCode();
        $resources = json_decode((string) $response->getBody(), true);
        return $resources;*/
    }

    public function features()
    {
        $newFeatures = 0;
        $updatedFeatures = 0;
        $features = $this->getResources('features');
        //dd($features);

        foreach ($features as $feature)
        {
            $existingFeature = DealersFeatures::where('slug', $feature['slug'])->first();
            if($existingFeature)
            {
              $existingFeature->name = $feature['name'];
              $existingFeature->icon = $feature['icon'];
              $existingFeature->save();
              $updatedFeatures++;
            } else {
                DealersFeatures::create([
                    'name' => $feature['name'],
                    'slug' => $feature['slug'],
                    'icon' => isset($feature['icon']) ? $feature['icon'] : null,
                ]);
                $newFeatures++;
            }
        }
        if($newFeatures > 0 ) $this->logImport('New Features', $newFeatures);
        if($updatedFeatures > 0 ) $this->logImport('Updated Features', $updatedFeatures);
    }

    public function categories()
    {
        $newCategories = 0;
        $updatedCategories = 0;
        $categories = $this->getResources('categories');
        //dd($categories);

        if(count($categories) == 0) return null;

        foreach($categories as $parent){
            $parentCategory = null;
            foreach ($parent as $child){
                if(!$parentCategory){
                    $parentCategory = DealerCategories::where('slug', $child['parent']['slug'])->first();
                    if(!$parentCategory){
                        $parentCategory = DealerCategories::create([
                            'name' => $child['parent']['name'],
                            'slug' => $child['parent']['slug'],
                            'description' => $child['parent']['description'],
                            'icon' => ($child['parent']['icon'] != '0') ? $child['parent']['icon'] : null,
                            'parent_id' => null,
                            'user_id' => null,
                            'system' => 0,
                        ]);
                    } else {
                        $parentCategory->name = $child['parent']['name'];
                        $parentCategory->description = $child['parent']['description'];
                        $parentCategory->icon = ($child['parent']['icon'] != '0') ? $child['parent']['icon'] : null;
                        $parentCategory->save();
                    }
                }

                $childCategory = DealerCategories::where('slug', $child['slug'])->first();
                if(!$childCategory){
                    DealerCategories::create([
                        'name' => $child['name'],
                        'slug' => $child['slug'],
                        'description' => $child['description'],
                        'icon' => ($child['icon'] != '0') ? $child['icon'] : null,
                        'parent_id' => $parentCategory->id,
                        'area' => $child['area'],
                        'user_id' => null,
                        'system' => 0,
                    ]);
                    $newCategories++;
                } else {
                    $childCategory->name = $child['name'];
                    $childCategory->description = $child['description'];
                    $childCategory->icon = ($child['icon'] != '0') ? $child['icon'] : null;
                    $childCategory->save();
                    $updatedCategories++;
                }
            }

        }
        if($newCategories > 0 ) $this->logImport('New Categories', $newCategories);
        if($updatedCategories > 0 ) $this->logImport('Updated Categories', $updatedCategories);
    }


    public function dealers()
    {
        $newDealers = 0;
        $updatedDealers = 0;

        $dealers = $this->getResources('updatedDealers');
        dd($dealers);
        if(count($dealers) == 0) return null;

        foreach($dealers as $dealer)
        {
            $existingDealer = Dealers::where('slug', $dealer['slug'])->first();
            if(!$existingDealer){
                $auctioneer = new Dealers();
                $auctioneer->name = trim($dealer['name']);
                $auctioneer->address = trim($dealer['address']);
                $auctioneer->postcode = trim($dealer['postcode']);
                $auctioneer->longitude = isset($dealer['longitude']) ? $dealer['longitude'] : '';
                $auctioneer->latitude = isset($dealer['latitude']) ? $dealer['latitude'] : '';
                $auctioneer->town = trim($dealer['town']);
                $auctioneer->county = trim($dealer['county']);
                $auctioneer->phone = trim($dealer['phone']);
                $auctioneer->fax = isset($dealer['fax']) ? trim($dealer['fax']) : '';
                $auctioneer->email = isset($dealer['email']) ? trim($dealer['email']) : '';
                $auctioneer->website = isset($dealer['website']) ? trim($dealer['website']) : '';
                $auctioneer->auction_url = isset($dealer['auction_url']) ? trim($dealer['auction_url']) : '';
                $auctioneer->online_bidding_url = isset($dealer['online_bidding_url']) ? trim($dealer['online_bidding_url']) : '';
                $auctioneer->status = trim($dealer['status']);
                $auctioneer->details = trim($dealer['details']);
                $auctioneer->buyers_premium = isset($dealer['buyers_premium']) ? trim($dealer['buyers_premium']) : '';
                $auctioneer->directions = isset($dealer['directions']) ? trim($dealer['directions']) : '';
                $auctioneer->rail_station = isset($dealer['rail_station']) ? trim($dealer['rail_station']) : '';
                $auctioneer->notes = trim($dealer['notes']);
                $auctioneer->type = $dealer['type'];
                $auctioneer->country_id = '826';

                $auctioneer->save();

                $newDealers++;

                if($dealer->features && count($dealer['features']) > 0){}

                if($dealer['categories'] && count($dealer['categories']) > 0){
                    $catArray = [];
                    foreach($dealer['categories'] as $category){
                        if($curCat = DealerCategories::where('slug', $category['slug'])->first()){
                            array_push($catArray, $curCat->id);
                        }
                        $auctioneer->categories()->sync($catArray);
                    }
                }

            } else {
                $existingDealer->name = trim($dealer['name']);
                $existingDealer->address = trim($dealer['address']);
                $existingDealer->postcode = trim($dealer['postcode']);
                $existingDealer->longitude = isset($dealer['longitude']) ? $dealer['longitude'] : '';
                $existingDealer->latitude = isset($dealer['latitude']) ? $dealer['latitude'] : '';
                $existingDealer->town = trim($dealer['town']);
                $existingDealer->county = trim($dealer['county']);
                $existingDealer->phone = trim($dealer['phone']);
                $existingDealer->fax = isset($dealer['fax']) ? trim($dealer['fax']) : '';
                $existingDealer->email = isset($dealer['email']) ? trim($dealer['email']) : '';
                $existingDealer->website = isset($dealer['website']) ? trim($dealer['website']) : '';
                $existingDealer->auction_url = isset($dealer['auction_url']) ? trim($dealer['auction_url']) : '';
                $existingDealer->online_bidding_url = isset($dealer['online_bidding_url']) ? trim($dealer['online_bidding_url']) : '';
                $existingDealer->status = trim($dealer['status']);
                $existingDealer->details = trim($dealer['details']);
                $existingDealer->buyers_premium = isset($dealer['buyers_premium']) ? trim($dealer['buyers_premium']) : '';
                $existingDealer->directions = isset($dealer['directions']) ? trim($dealer['directions']) : '';
                $existingDealer->rail_station = isset($dealer['rail_station']) ? trim($dealer['rail_station']) : '';
                $existingDealer->notes = trim($dealer['notes']);
                $existingDealer->type = $dealer['type'];
                $existingDealer->country_id = '826';
                $existingDealer->save();

                $updatedDealers++;

                if($dealer->features && count($dealer['features']) > 0){}

                if($dealer['categories'] && count($dealer['categories']) > 0){
                    $catArray = [];
                    foreach($dealer['categories'] as $category){
                        if($curCat = DealerCategories::where('slug', $category['slug'])->first()){
                            array_push($catArray, $curCat->id);
                        }
                        $existingDealer->categories()->sync($catArray);
                    }
                }
            }
        }
        if($newDealers > 0 ) $this->logImport('New Dealers', $newDealers);
        if($updatedDealers > 0 ) $this->logImport('Updated Dealers', $updatedDealers);
    }


    public function parsedDealers()
    {
        $parsedDealers = 0;
        $dealers = $this->getResources('parsedDealers');
        //dd($dealers);
        if(count($dealers) == 0) return null;

        foreach ($dealers as $dealer)
        {
            $existingParsed = ParsedDealers::where('slug', $dealer['slug'])->first();
            if($existingParsed) continue;
            ParsedDealers::create([
                'slug' => $dealer['slug']
            ]);
            $parsedDealers++;
        }
        if($parsedDealers > 0 ) $this->logImport('Parsed Dealers', $parsedDealers);
    }

    public function getLots()
    {
        $this->logImport('Started');
        $lotCount = 0;
        $dealerCount = ParsedDealers::where('processing', 'no')->where('error', 'no')->orderBy('id', 'ASC')->count();
        if($dealerCount == 0) return null;
        $dealer = ParsedDealers::where('processing', 'no')->where('error', 'no')->orderBy('id', 'ASC')->first();
        if(!$dealer) return null;
        ParsedDealers::where('id', $dealer->id)->update(['processing' => 'yes']);
        $this->auctioneer = Dealers::where('slug', $dealer->slug)->first();
        $dealerLots = $this->getResources('getLots', $dealer->slug);
        //dd($dealerLots);
        if(!$dealerLots) {
            $dealer->delete();
            return null;
        }

        $this->currentCategoryType = $dealerLots['type'];

            foreach($dealerLots['catalogues'] as $catalogue){
                foreach($catalogue['lots'] as $lot){
                    $this->currentlot = $lot;
                    $this->processLot();
                    $lotCount++;
                }
            }

        $dealer->delete();
        if($lotCount > 0 ) $this->logImport('Lots', $lotCount);
        $dealerCount = ParsedDealers::count();
        if($dealerCount == 0){

        }
    }


    private function processLot()
    {

        if(!$vehicle = Vehicles::where('url', $this->currentlot['lot_id'])->where('dealer_id', $this->auctioneer->id)->first())
        {
            if($this->debug) echo 'Make : '.$this->currentlot['manufacturer'].' - Model : '.$this->currentlot['model'].PHP_EOL;
            $this->sanitiseLot();
            $makeID = $this->getMakeId($this->currentlot['manufacturer']);
            if($this->debug) echo 'Make Id :'.$makeID.PHP_EOL;
            $modelID = $this->getModelId($makeID, $this->currentlot['model']);
            if($this->debug) echo 'Model Id :'.$modelID.PHP_EOL;
            if($this->debug) exit();
            $typeID = $this->getTypeId($this->currentlot['vehicle_type']);
            $engineSize = $this->getEngineSize($this->currentlot['engine_size']);
            $bodyType = $this->getBodyType($this->currentlot['type']);

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
            if(!in_array(strtolower($this->currentlot['gearbox']), $gearbox)) $this->currentlot['gearbox'] = 'unlisted';

            $fuel = ['petrol','diesel','electric','hybrid','lpg','unlisted'];
            if(!in_array(strtolower($this->currentlot['fuel']), $fuel)) $this->currentlot['fuel'] = 'unlisted';

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

    private function sanitiseLot()
    {
        foreach ($this->currentlot as $lotKey => $lot)
        {
            if($lotKey == 'images') continue;
            if(!is_array($lot)) $this->currentlot[$lotKey] =  trim(filter_var($lot, FILTER_SANITIZE_STRING));
        }
    }

    public function getMakeId($manufacturer)
    {
        if (!isset($manufacturer)) return 1;
        if (trim($manufacturer) == '') return 1;
        $manu = strtolower($manufacturer);
        //if(!makeAllowed($manu)) return 1;
        //if($make = VehicleMake::where('name', $manu)->first()) return $make->id;
        if ($make = $this->vehicleToolsService->getExactMake($manu, true)) return $make->id;
        return 1;
    }

    public function getModelId($makeId, $model)
    {
        if ($makeId == 1) return 1;
        if (!isset($model)) return 1;
        if ($model == '') return 1;
        $vmodel = strtolower(trim($model));
        if ($model = $this->vehicleToolsService->getExactModel($vmodel, $makeId, true)) return $model->id;

        return 1;
    }

    public function getTypeId($vehicle_type)
    {
        if (is_null($this->vehicleTypes)) $this->vehicleTypes = $this->vehicleTypeService->getCachedTypes();

        if (!isset($vehicle_type)) {
            $vtype = $this->vehicleTypes->where('slug', 'car')->all();
            return $vtype[0]->id;
        }

        $vtype = $this->vehicleTypes->where('name', $vehicle_type)->all();
        if ($vtype) return $vtype[0]->id;
        //unlisted
        $vtype = $this->vehicleTypes->where('slug', 'unlisted')->all();
        return $vtype[0]->id;

    }

    public function getEngineSize($engine_size)
    {
        if (is_null($this->engineSize)) $this->engineSize = VehicleEngineSize::get();

        if (isset($engine_size) && $engine_size != '') {
            if ($engineSize = $this->engineSize->where('slug', $engine_size)->first()) {
                return $engineSize;
            }
        }
        return false;
    }
    public function getBodyType($type)
    {
        if (is_null($this->bodyType)) $this->bodyType = VehicleBodyType::get();

        if (isset($type) && $type != '') {
            if ($bodyType = $this->bodyType->where('slug', str_slug($type))->first()) {
                return $bodyType;
            }
        }
        return false;
    }

    private function logImport($type="auctions", $amount=0)
    {
            \Activitylogger::log('Import - '.$type.' : '.$amount, null);
    }


}