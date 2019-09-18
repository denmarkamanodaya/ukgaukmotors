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

class Bootstrap
{
    public static function row(ShortcodeInterface $s)
    {
        return '<div class="row">'.$s->getContent().'</div>';
    }

    public static function col(ShortcodeInterface $s)
    {
        $size = 12;
        if($s->getParameter('size'))
        {
            $size = $s->getParameter('size');
        }
        return '<div class="col-md-'.$size.'">'.$s->getContent().'</div>';
    }

}