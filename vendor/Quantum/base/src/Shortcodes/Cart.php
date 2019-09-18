<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Cart.php
 **/

namespace Quantum\base\Shortcodes;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;


class Cart
{

    public static function index(ShortcodeInterface $s)
    {
        $url = '';
        $members = \Auth::check() ? 'members/' : '';
        if($s->getParameter('membership'))
        {
            $membership = $s->getParameter('membership');
            $url = url($members.'cart/add/membership/'.$membership);
        }
        
        return $url;
    }

}