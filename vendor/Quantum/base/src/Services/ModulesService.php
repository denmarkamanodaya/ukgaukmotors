<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : ModulesService.php
 **/

namespace Quantum\base\Services;


use File;
use Quantum\base\Models\Modules;

class ModulesService
{
    private $quantumPath;

    private $availableModules;

    private $installedModules;

    private $moduleJson;

    private $currentModule;
    
    private $moduleInfo;

    protected $loadOrder = ['base', 'blog', 'notifications' , 'newsletter', 'tickets', 'calendar'];

    
    private function setup()
    {
        $this->setPath();
        $this->setInstalledModules();  
    }

    public function installModules()
    {
        $this->setup();
        $this->appModule();
        $this->readModules();
        $this->processModules();
    }

    private function setInstalledModules()
    {
        $this->installedModules = Modules::tenant()->get();
    }

    private function setPath()
    {
        $env = config('main.app_type');
        if($env != 'package')
        {
            $this->quantumPath = base_path('/vendor/Quantum');
        } else {
            $this->quantumPath = base_path('/packages/Quantum');
        }
    }

    private function appModule()
    {
        $this->currentModule = base_path();
        $this->moduleJson = $this->getModuleJson('/app');
        if($this->moduleJson) $this->processJson();
        $this->isInstalled('/app/Module/Module.php');
    }
    
    private function readModules()
    {
        $this->availableModules = File::directories($this->quantumPath);
        $this->setOrder();
    }

    private function setOrder()
    {
        $newOrder = [];
        foreach ($this->loadOrder as $loadItem)
        {
            foreach ($this->availableModules as $key => $value)
            {
                if(ends_with($value, $loadItem)) {
                    array_push($newOrder, $value);
                    unset($this->availableModules[$key]);
                }
            }
        }
        //set remain
        $this->availableModules = array_merge($newOrder, $this->availableModules);
    }

    private function processModules()
    {
        foreach ($this->availableModules as $module)
        {
            $this->currentModule = $module;
            $this->moduleJson = $this->getModuleJson('/src');
            if($this->moduleJson) $this->processJson();
            $this->isInstalled('/src/Module/Module.php');
        }
    }

    private function getModuleJson($folder)
    {
        if(File::exists($this->currentModule.$folder.'/Module/module.json'))
        {
            return File::get($this->currentModule.$folder.'/Module/module.json');
        }
        return false;
    }

    private function processJson()
    {
        $this->moduleInfo = json_decode($this->moduleJson, true);
    }
    
    private function isInstalled($modulePath)
    {
        $slug = $this->moduleInfo['module']['slug'];

            $module = $this->installedModules->where('slug', $slug)->first();
            if($module)
            {
                $this->updateModule($module, $modulePath);
            } else {
                $this->installModule($modulePath);
            }
        return;
    }
    
    private function installModule($modulePath)
    {
        //Add to db
        Modules::create($this->moduleInfo['module']);
        //run install script if present
        $this->runModule($modulePath,'install');
    }
    
    private function updateModule($module, $modulePath)
    {
        //update db
        $module->update($this->moduleInfo['module']);
        //run update script if present
        $this->runModule($modulePath);
    }
    
    private function runModule($path,$type = 'update')
    {
        if(File::exists($this->currentModule.$path))
        {
            $className = $this->moduleInfo['module']['namespace'] . '\\Module\\Module';
            $installer = new $className;
            if($type == 'install')
            {
                if(config('main.multisite'))
                {
                    $installer->installMultiSite();
                } else {
                    $installer->install();
                }
            } else {
                $installer->update();
            }
            
        }
    }
}