<?php

namespace Quantum\base\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class User
{
    public static function username(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->username;
        }
        return '';
    }

    public static function email(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->email;
        }
        return '';
    }

    public static function firstname(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->profile->first_name;
        }
        return '';
    }

    public static function lastname(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->profile->last_name;
        }
        return '';
    }

    public static function address(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->profile->address;
        }
        return '';
    }

    public static function address2(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->profile->address2;
        }
        return '';
    }

    public static function city(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->profile->city;
        }
        return '';
    }

    public static function county(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->profile->county;
        }
        return '';
    }

    public static function postcode(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->profile->postcode;
        }
        return '';
    }

    public static function country(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return getCountry(\Auth::user()->profile->country);
        }
        return '';
    }

    public static function telephone(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->profile->telephone;
        }
        return '';
    }

    public static function bio(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            return \Auth::user()->profile->bio;
        }
        return '';
    }

    public static function avatar(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            $user = \Auth::user();
            /*$size = 200;
            if($s->getParameter('size'))
            {
                $sizes = [20,30,40,50,100,200,300];
                if(in_array($s->getParameter('size'), $sizes )) $size = $s->getParameter('size');
            }*/
            return show_profile_pic($user, 'img-responsive');
        }
        return '';
    }

    public static function logout(ShortcodeInterface $s)
    {
        if(\Auth::user())
        {
            \Auth::logout();
        }
        return '';
    }

}