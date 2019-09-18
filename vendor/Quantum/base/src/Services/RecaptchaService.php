<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : RecaptchaService.php
 **/

namespace Quantum\base\Services;



class RecaptchaService
{
    /**
     * @var Settings
     */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function updateSettings($request)
    {
        $this->settings->updateSetting('recaptcha_site_key', $request['recaptcha_site_key']);
        $this->settings->updateSetting('recaptcha_secret_key', $request['recaptcha_secret_key']);
        $this->settings->updateSetting('recaptcha_register', isset($request['recaptcha_register']) ? 1 : 0);
        $this->settings->updateSetting('recaptcha_login', isset($request['recaptcha_login']) ? 1 : 0);
        $this->settings->updateSetting('recaptcha_password', isset($request['recaptcha_password']) ? 1 : 0);
        $this->settings->updateSetting('recaptcha_guest_ticket', isset($request['recaptcha_guest_ticket']) ? 1 : 0);
        $this->settings->updateSetting('recaptcha_guest_newsletter', isset($request['recaptcha_guest_newsletter']) ? 1 : 0);
        $this->settings->clearCache();

        flash('Success : Recaptcha Settings have been updated.')->success();
    }

}