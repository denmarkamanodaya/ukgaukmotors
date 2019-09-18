<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Bootstrap.php
 **/

namespace Quantum\base\Shortcodes;


use Thunder\Shortcode\Shortcode\ShortcodeInterface;
use View;

class Forms
{
    public static function register(ShortcodeInterface $s)
    {
        $view = View::make('base::auth.register');
        $contents = $view->render();
        return $contents;
    }

    public static function contact(ShortcodeInterface $s)
    {
        $view = View::make('base::frontend.Contact.index');
        $contents = $view->render();
        return $contents;
    }

}