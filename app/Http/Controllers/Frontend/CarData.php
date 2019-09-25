<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : CarData.php
 **/

namespace App\Http\Controllers\Frontend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\CarDataService;
use App\Services\RestrictUserService;
use App\Services\VehicleService;
use Quantum\blog\Services\BlogService;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Services\SeoService;

class CarData extends Controller
{

    /**
     * @var CarDataService
     */
    private $carDataService;
    /**
     * @var BlogService
     */
    private $blogService;
    /**
     * @var RestrictUserService
     */
    private $restrictUserService;
    /**
     * @var VehicleService
     */
    private $vehicleService;

    private $seoService;
	
    public function __construct(CarDataService $carDataService, BlogService $blogService, RestrictUserService $restrictUserService, VehicleService $vehicleService, SeoService $seoService)
    {
        $this->carDataService = $carDataService;
        $this->blogService = $blogService;
        $this->restrictUserService = $restrictUserService;
	$this->vehicleService = $vehicleService;
	$this->seoService = $seoService;
    }

    public function index($only=null)
    {
        if($only)
        {
            if (ctype_alpha($only) && strlen($only) == 1) {
                $only = ucfirst($only);
            } else {
                $only = false;
            }
        }
        $carMakes = $this->carDataService->allMakes();
        $carMakesList = $carMakes->pluck('name', 'slug');
        $carMakesAlpha = $carMakes->groupBy(function ($item, $key) {
            return substr($item['name'], 0, 1);
        });
        if($only)
        {

            $carMakes = isset($carMakesAlpha[$only]) ? $carMakesAlpha[$only] : null;
        }
        $vehicleMakes[0] = 'Select Make';
        $carMakesList = array_merge($vehicleMakes,$carMakesList->toArray());
        $vehicleModels[0] = 'Select Model';
        $latestPosts = $this->blogService->latest_posts(['public']);
        if($only)
        {
            return view('frontend.Cars.Makes.index_only', compact('carMakes', 'latestPosts', 'carMakesList', 'vehicleModels', 'only', 'carMakesAlpha'));
        }
        return view('frontend.Cars.Makes.index', compact('carMakes', 'latestPosts', 'carMakesList', 'vehicleModels', 'carMakesAlpha'));

    }

    public function show($id)
    {
        //limit car view
        if($this->restrictUserService->restrictView('displayedCM')) return redirect('/register');
        $this->restrictUserService->updateCount('displayedCM');
        //end limit

        $carMake = $this->carDataService->carMake($id);
        $relatedVehicles = $this->vehicleService->relatedByMake($carMake);
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(5);

        $carMakes = $this->carDataService->allMakes();
        $carMakesList = $carMakes->pluck('name', 'slug');
        $vehicleMakes[0] = 'Select Make';
        $carMakesList = array_merge($vehicleMakes,$carMakesList->toArray());
	$vehicleModels[0] = 'Select Model';

	// Seo
	preg_match_all('|<h2>(.*)</h2>|iU', $carMake->description->content, $headings);
	$seoData = (object) array(
		'title' 	=> $carMake->name . " Motorpedia ALL models, history and specifications",
		'description' 	=> strip_tags($headings[1][0])
	);
	$this->seoService->motorpedia($seoData);

        return view('frontend.Cars.Makes.show', compact('carMake', 'latestPosts', 'carMakesList', 'vehicleModels', 'relatedVehicles'));
    }

    public function showModel($make, $model)
    {
        //limit car view
        if($this->restrictUserService->restrictView('displayedCMO')) return redirect('/register');
        $this->restrictUserService->updateCount('displayedCMO');
        //end limit

        $data = $this->carDataService->getModel($make, $model);
        $carMake = $data['carMake'];
        $carModel = $data['carModel'];
        $relatedVehicles = $this->vehicleService->relatedByModel($carModel, $carMake);
        $latestPosts = $this->blogService->latest_posts(['public']);
        $latestPosts = $latestPosts->take(5);
        $carMakes = $this->carDataService->allMakes();
        $carMakesList = $carMakes->pluck('name', 'slug');
        $vehicleMakes[0] = 'Select Make';
        $carMakesList = array_merge($vehicleMakes,$carMakesList->toArray());
	$vehicleModels[0] = 'Select Model';

	// Seo
        preg_match_all('|<h2>(.*)</h2>|iU', $carMake->description->content, $headings);
        $seoData = (object) array(
                'title'         => $carMake->name . " " . $carModel['name'] . " Motorpedia ALL models, history and specifications",
		'description'   => strip_tags($headings[1][0]),
        );
        $this->seoService->motorpedia($seoData);

        unset($data);
        return view('frontend.Cars.Models.show', compact('carMake', 'carModel', 'latestPosts', 'carMakesList', 'vehicleModels', 'relatedVehicles'));
    }

    public function showModelVariant($make, $model, $variant)
    {
        return redirect('/register');
        /*$data = $this->carDataService->getVariant($make, $model, $variant);
        $carMake = $data['carMake'];
        $carModel = $data['carModel'];
        $carVariant = $data['carVariant'];
        $latestPosts = $this->blogService->latest_posts(['members', 'public']);
        $latestPosts = $latestPosts->take(5);
        unset($data);
        return view('members.Cars.Variant.show', compact('carMake', 'carModel', 'carVariant', 'latestPosts'));*/
    }

    public function search(Requests\Members\CarDataSearchRequest $request)
    {
        if($request->vehicleMakeData == '0') return redirect('/motorpedia/');
        if($request->vehicleModel == '0') return redirect('/motorpedia/car-make/'.$request->vehicleMakeData);
        return redirect('/motorpedia/car-make/'.$request->vehicleMakeData.'/'.$request->vehicleModel);
    }

    public function modelsData($id)
    {
        $carMake = $this->carDataService->carMake($id);

        return Datatables::of($carMake->models)
            ->editColumn('featureImage', function($model) use($carMake) {
                if($model->description && $model->description->featured_image != '')
                {
                    return '
                        <a href="'.url('motorpedia/car-make/'.$carMake->slug.'/'.$model->slug).'"><img class="img-responsive" src="'.featuredImage($model->description->featured_image).'" alt="" /></a>
                        ';
                } else {
                    return '';
                }

            })
            ->editColumn('content', function($model) use($carMake) {

                if($model->description)
                {
                    return '<div class="cs-text">
                        <div class="post-title">
                        <h4><a href="'.url('motorpedia/car-make/'.$carMake->slug.'/'.$model->slug).'">'.$model->name.'</a></h4>
                        </div>
                        <p>'.makeSummary($model->description->content).'</p>
                        <div class="text-right widget-text"><a class="contact-btn cs-color" href="'.url('motorpedia/car-make/'.$carMake->slug.'/'.$model->slug).'">View Details</a></div>
                        </div>';
                } else {
                    return '<div class="cs-text">
                        <div class="post-title">
                        <h4><a href="'.url('motorpedia/car-make/'.$carMake->slug.'/'.$model->slug).'">'.$model->name.'</a></h4>
                        </div>
                        <div class="text-right widget-text"><a class="contact-btn cs-color" href="'.url('motorpedia/car-make/'.$carMake->slug.'/'.$model->slug).'">View Details</a></div>
                        </div>';
                }
            })
            ->editColumn('name', function($model) {
                return $model->name;
            })
            ->rawColumns(['content', 'featureImage'])
            ->make(true);
    }

}
