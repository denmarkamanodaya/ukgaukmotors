<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Cart.php
 **/

namespace Quantum\base\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Quantum\base\Services\PaymentService;

class Payment extends Controller
{

    /**
     * @var PaymentService
     */
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index($gateway)
    {
        return $this->paymentService->paymentPrepare($gateway);
    }
    
    public function success($type, $gateway)
    {
        return $this->paymentService->success($type, $gateway);
    }
    
    
    public function cancel($type, $gateway)
    {
        return $this->paymentService->cancel($type, $gateway);
    }
    

}