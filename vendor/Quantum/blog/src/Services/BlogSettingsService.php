<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : MembershipSettingsService.php
 **/

namespace Quantum\blog\Services;

use Quantum\base\Services\Settings;

class BlogSettingsService
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
        $this->settings->updateSetting('enable_blog',$request->enable_blog);
        $this->settings->updateSetting('blog_link_structure',$request->blog_link_structure);
        $this->settings->clearCache();
        flash('Success : Settings has been updated.')->success();
        return;
    }

}