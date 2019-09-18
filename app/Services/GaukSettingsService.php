<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : GaukSettingsService.php
 **/

namespace App\Services;

use Quantum\base\Services\Settings;

class GaukSettingsService
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
        $this->settings->updateSetting('gauk_import_api_key',$request->gauk_import_api_key);
        $this->settings->updateSetting('gauk_import_api_status',$request->gauk_import_api_status);
        $this->settings->updateSetting('google_map_api_key',$request->google_map_api_key);
        $this->settings->updateSetting('main_content_role',$request->main_content_role);
        $this->settings->clearCache();
        \Flash::success('Success : Settings has been updated.');
        return;
    }
}