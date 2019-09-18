<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Settings.php
 **/

namespace Quantum\base\Services;


use Cache;
use Quantum\base\Models\Settings as SiteSettings;

class Settings
{
    public static function get($key)
    {
        $settings = self::getAll();
        if(isset($settings[$key])) return $settings[$key];
        return null;
        //return $settings;
    }

    private static function getAll()
    {
        $settings = Cache::rememberForever('site.settings', function() {
            return SiteSettings::tenant()->pluck('data', 'name')->toArray();
        });
        return $settings;
    }

    public function updateSettings($request)
    {
        $setting = \Quantum\base\Models\Settings::where('name', 'site_name')->tenant()->firstOrFail();
        $setting->data = $request->site_name;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'site_email_address')->tenant()->firstOrFail();
        $setting->data = $request->site_email_address;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'site_email_from_name')->tenant()->firstOrFail();
        $setting->data = $request->site_email_from_name;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'public_theme')->tenant()->firstOrFail();
        $setting->data = $request->public_theme;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'members_theme')->tenant()->firstOrFail();
        $setting->data = $request->members_theme;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'admin_theme')->tenant()->firstOrFail();
        $setting->data = $request->admin_theme;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'email_theme')->tenant()->firstOrFail();
        $setting->data = $request->email_theme;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'contact_thankyou_page')->tenant()->firstOrFail();
        $setting->data = $request->contact_thankyou_page;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'members_home_page')->tenant()->firstOrFail();
        $setting->data = $request->members_home_page;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'members_home_page_title')->tenant()->firstOrFail();
        $setting->data = $request->members_home_page_title;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'site_country')->tenant()->firstOrFail();
        $setting->data = $request->site_country;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'members_checkout_page')->tenant()->firstOrFail();
        $setting->data = $request->members_checkout_page;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'public_checkout_page')->tenant()->firstOrFail();
        $setting->data = $request->public_checkout_page;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'members_upgrade_page')->tenant()->firstOrFail();
        $setting->data = $request->members_upgrade_page;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'members_deleted_account_page')->tenant()->firstOrFail();
        $setting->data = $request->members_deleted_account_page;
        $setting->save();

        $setting = \Quantum\base\Models\Settings::where('name', 'use_gravatar')->tenant()->firstOrFail();
        $setting->data = $request->use_gravatar;
        $setting->save();

        flash('Success : Settings has been updated.')->success();
        $this->clearCache();
        return;
    }
    
    public function updateSetting($key, $value)
    {
        if($setting = \Quantum\base\Models\Settings::where('name', $key)->tenant()->first()){
            $setting->data = $value;
            $setting->save();  
        }
    }

    public function clearCache()
    {
        \Cache::forget('site.settings');
        \Countries::clearCache();
    }

}