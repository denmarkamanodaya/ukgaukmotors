<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : CacheService.php
 **/

namespace Quantum\base\Services;


class CacheService
{

    /**
     * @var MenuService
     */
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }
    
    public function clearUserCache($user)
    {
        if(is_object($user))
        {
            $userid = $user->id;
        } else {
            $userid = $user;
        }
         
        $this->menuService->clearCacheUser($userid);
        
    }

}