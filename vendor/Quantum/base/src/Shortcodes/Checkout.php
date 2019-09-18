<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Again.php
 **/

namespace Quantum\base\Shortcodes;

use Quantum\base\Services\CheckoutService;
use Thunder\Shortcode\Shortcode\ShortcodeInterface;

class Checkout
{

    /**
     * @var CheckoutService
     */
    private $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public static function index(ShortcodeInterface $s)
    {
        $co = new \Quantum\base\Services\CheckoutService;
        return $co->create_checkout_page();
    }

}