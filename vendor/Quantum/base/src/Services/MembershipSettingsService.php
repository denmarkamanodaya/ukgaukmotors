<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : MembershipSettingsService.php
 **/

namespace Quantum\base\Services;

use Quantum\base\Services\Settings;

class MembershipSettingsService
{

    /**
     * @var Settings
     */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function update($request)
    {
        $this->settings->updateSetting('register_first_name',isset($request->register_first_name) ?: 0);
        $this->settings->updateSetting('register_last_name',isset($request->register_last_name) ?: 0);
        $this->settings->updateSetting('register_address',isset($request->register_address) ?: 0);
        $this->settings->updateSetting('register_address2',isset($request->register_address2) ?: 0);
        $this->settings->updateSetting('register_city',isset($request->register_city) ?: 0);
        $this->settings->updateSetting('register_county',isset($request->register_county) ?: 0);
        $this->settings->updateSetting('register_postcode',isset($request->register_postcode) ?: 0);
        $this->settings->updateSetting('register_country',isset($request->register_country) ?: 0);
        $this->settings->clearCache();
        flash('Success : Settings has been updated.')->success();
        return;
    }

}