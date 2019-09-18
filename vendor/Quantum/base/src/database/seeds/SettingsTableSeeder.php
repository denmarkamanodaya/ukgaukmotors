<?php
namespace Quantum\base\database\seeds;


use Quantum\base\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder {
    public function run() {
        //Settings::truncate();
        // add 1st row
        $setting = Settings::create( [
            'name' => 'site_name' ,
            'data' => 'Quantum Base' ,
        ] );

        $setting = Settings::create( [
            'name' => 'site_email_address' ,
            'data' => 'qfw@quantumscripts.com' ,
        ] );

        $setting = Settings::create( [
            'name' => 'site_email_from_name' ,
            'data' => 'Quantum Base' ,
        ] );

        $setting = Settings::create( [
            'name' => 'public_theme' ,
            'data' => '4' ,
        ] );

        $setting = Settings::create( [
            'name' => 'members_theme' ,
            'data' => '4' ,
        ] );

        $setting = Settings::create( [
            'name' => 'admin_theme' ,
            'data' => '4' ,
        ] );


    }
}