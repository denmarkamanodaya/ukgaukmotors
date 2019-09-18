<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Payment.php
 **/

namespace Quantum\base\Repositories;


class Payment
{
    protected $gateway;

    public function __construct()
    {
    }

    private function getGateway($gateway)
    {
        $gateway = '\Quantum\base\Repositories\Gateway\\'.$gateway.'\\'.$gateway;
        if (class_exists($gateway)) {
            return new $gateway($this);
        }
        else {
            throw new \Exception('Gateway does not exist ' . $gateway);
        }
    }

    public function setGateway($gateway = NULL) {
        if (is_null($gateway)) {
            $gateway = 'PaypalRest';
        }
        $this->gateway = $this->getGateway($gateway->slug);
        return $this->gateway;
    }
}