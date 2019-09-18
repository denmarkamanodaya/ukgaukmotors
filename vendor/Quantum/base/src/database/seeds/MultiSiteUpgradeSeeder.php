<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : MultiSiteUpgradeSeeder.php
 **/

namespace Quantum\base\database\seeds;

use DB;
use Illuminate\Database\Seeder;

class MultiSiteUpgradeSeeder extends Seeder
{

    public function run()
    {
        $appName = config('app.name');

        DB::table('modules')->whereNull('tenant')->update(['tenant' => $appName]);
        DB::table('pages')->whereNull('tenant')->update(['tenant' => $appName]);
        DB::table('membership_types')->whereNull('tenant')->update(['tenant' => $appName]);
        DB::table('settings')->whereNull('tenant')->update(['tenant' => $appName]);
        DB::table('menu')->whereNull('tenant')->update(['tenant' => $appName]);
        DB::table('news')->whereNull('tenant')->update(['tenant' => $appName]);
        DB::table('site_categories')->whereNull('tenant')->update(['tenant' => $appName]);
        DB::table('site_tags')->whereNull('tenant')->update(['tenant' => $appName]);
        DB::table('calendar_events')->whereNull('tenant')->update(['tenant' => $appName]);
        DB::table('emails')->whereNull('tenant')->update(['tenant' => $appName]);

        \Cache::clear();
    }
}