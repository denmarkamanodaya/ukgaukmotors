<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : CountryService.php
 **/

namespace Quantum\base\Services;

use Cache;
use Quantum\base\Models\Countries;

class CountryService
{

    /**
     * Get the countries from the JSON file, if it hasn't already been loaded.
     *
     * @return array
     */
    public function seedCountries()
    {
        //Get the countries from the JSON file
        $countries = json_decode(file_get_contents(__DIR__ . '/countries.json'), true);
        return $countries;
    }

    public function siteCountry()
    {
        $sitecountry = Cache::rememberForever('siteCountry', function() {
            return Countries::where('iso_3166_3', \Settings::get('site_country'))->first();
        });
        return $sitecountry;
    }
    
    public function clearCache()
    {
        Cache::forget('siteCountry');
    }

}