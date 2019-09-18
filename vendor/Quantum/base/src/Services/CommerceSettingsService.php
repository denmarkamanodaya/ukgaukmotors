<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : CommerceSettingsService.php
 **/

namespace Quantum\base\Services;


use Quantum\base\Services\Settings;

class CommerceSettingsService
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
        $this->settings->updateSetting('PaypalRest_ClientId',$request->PaypalRest_ClientId);
        $this->settings->updateSetting('PaypalRest_ClientSecret',$request->PaypalRest_ClientSecret);
        $this->settings->updateSetting('PaypalRest_mode',$request->PaypalRest_mode);
        $this->settings->updateSetting('PaypalRest_ipn_passthrough',$request->PaypalRest_ipn_passthrough);
        $this->settings->updateSetting('PaypalRest_Hook_Id',$request->PaypalRest_Hook_Id);
        $this->settings->clearCache();
        \flash('Success : Settings has been updated.')->success();
        return;
    }

}