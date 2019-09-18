<?php

namespace Quantum\base\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class Role
{
    public static function role(ShortcodeInterface $s)
    {

        $only = $s->getParameter('only');
        if($only)
        {
            if(\Auth::user())
            {
                $onlyRole = true;
                $gotRole = false;
                foreach (\Auth::user()->roles as $role)
                {
                    if($role->name == $only)
                    {
                        $gotRole = true;
                    } else {
                        if($role->name != 'super_admin')
                        {
                            if($role->name != 'admin')
                            {
                                $onlyRole = false;
                            }
                        }
                    }
                }
                if($gotRole == true && $onlyRole == true) return $s->getContent();
            }
            return '';
        }

        $role = $s->getParameter('name') ? strtolower($s->getParameter('name')) : 'member';
        if(\Auth::user())
        {
            $roles = explode(",", $role);
            foreach ($roles as $role)
            {
                if (\Auth::user()->hasRole($role))
                {
                    return $s->getContent();
                }
            }
        }
        return '';
    }

    

}