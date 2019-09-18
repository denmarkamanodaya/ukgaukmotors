<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : ThemeService.php
 **/

namespace Quantum\base\Services;


class ThemeService
{
    protected $themePath;
    protected $themeUrl;
    protected $viewThemePath;

    public function __construct()
    {
        $this->themePath = config('main.public_path').'/theme';
        $this->themeUrl = config('app.url').'/theme';
        $this->viewThemePath = base_path()."/resources/views/Theme";
    }

    public function template($area, $file='Template')
    {
        $template = 'Theme.'.Settings::get($area.'_theme').'.'.$area.'.'.$file;
        return $template;
    }

    public function navigation($area)
    {
        $template = 'Theme.'.Settings::get($area.'_theme').'.'.$area.'.Navigation';
        return $template;
    }
    
    public function asset($asset, $area)
    {
        return $this->themeUrl.'/'.Settings::get($area.'_theme').'/assets/'.$asset;
    }
    
    public function includeFile($file, $area)
    {
        $template = 'Theme.'.Settings::get($area.'_theme').'.'.$area.'.'.$file;
        return $template;
    }

    public function themeList()
    {
        $themes = [];

        $directories = array_map('basename', \File::directories($this->viewThemePath));
        $directories = array_diff($directories, ['.', '..']);

        foreach ($directories as $directory)
        {
            $themes[$directory] = $directory;
        }
        return $themes;

    }

}