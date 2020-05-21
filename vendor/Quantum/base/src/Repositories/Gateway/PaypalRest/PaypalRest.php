<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : PaypalRest.php
 **/

namespace Quantum\base\Repositories\Gateway\PaypalRest;

use Carbon\Carbon;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Quantum\base\Models\BillingPlan;
use Quantum\base\Models\PaymentGateway;
use Quantum\base\Models\Transactions;
use Quantum\base\Repositories\Gateway\PaypalRest\WebHookListener;
use Quantum\base\Repositories\Payment as PaymentInstance;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Api\Agreement;
use PayPal\Api\ShippingAddress;
use Settings;

class PaypalRest
{

    /**
     * object to authenticate the call.
     * @param object $_apiContext
     */
    private $_apiContext;

    /**
     * Set the ClientId and the ClientSecret.
     * @param
     *string $_ClientId
     *string $_ClientSecret
     */
    private $_ClientId = 'AYmIh7itn7cHQq47bmiNaSz0fTJuhEmbCfnKBOU8Rbx_rHITQALKrEgcvbOjhrDi3R8bJzgI5wyv2YjM';
    private $_ClientSecret ='ELhPG-EET5EA3RUZ70ln3RREb3VymCAVgyHTaz4xO_2uGhuBVxy-XN1zgNcqkw5pv3ry1TE6SLmwHWDu';
    private $returnUrl = '';
    private $cancelUrl = '';

    protected $total;
    protected $items = [];
    protected $sitecountry;
    protected $listener;
    protected $ipnRequest;

    public function __construct(PaymentInstance $instance) {
        $this->instance = $instance;
        $this->setConfig();
        $this->setLocaleInfo();
        $this->setUrls();
        $this->checkLogDir();
        $this->_api_context = new ApiContext(new OAuthTokenCredential($this->_ClientId, $this->_ClientSecret));
        $this->_api_context->setConfig([
            'mode' => Settings::get('PaypalRest_mode'),
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path().'/logs/PaypalRest/PayPal.log',
            'log.LogLevel' => 'FINE'
        ]);
    }
    private function setUrls($type = 'sale')
    {
        $member = \Auth::check() ? 'members/' : '';
        $this->returnUrl = url($member.'payment/'.$type.'/success/PaypalRest');
        $this->cancelUrl = url($member.'payment/'.$type.'/cancel/PaypalRest');

    }

    private function checkLogDir()
    {
        if(!is_dir(storage_path().'/logs/PaypalRest')) mkdir(storage_path().'/logs/PaypalRest');
    }

    private function setLocaleInfo()
    {
        $this->sitecountry = \Countries::siteCountry();
    }

    private function setConfig()
    {
        $this->_ClientId = Settings::get('PaypalRest_ClientId');
        $this->_ClientSecret = Settings::get('PaypalRest_ClientSecret');
    }

    public function addItem($name, $amount, $qty = 1)
    {
        $item = new Item();
        $item->setName($name)
            ->setCurrency($this->sitecountry->currency_code)
            ->setQuantity($qty)
            ->setPrice($amount);
        array_push($this->items, $item);
        $this->total = $this->total + $amount;
    }

    public function addSubscription($item)
    {
        return $this->sub_plan_exists($item);
    }

    private function sub_plan_exists($item)
    {
        if($subPlan = BillingPlan::where('membership_types_id', $item->model_id)
            ->where('amount', $item->price)
            ->where('subscription_period_amount', $item->product->subscription_period_amount)
            ->where('subscription_period_type', $item->product->subscription_period_type)
            ->where('membership_update', $item->product->updated_at->toDateTimeString())
            ->first()){
            return $subPlan;
        } else {
            return $this->create_subPlan($item);
        }
    }

    private function create_subPlan($item)
    {
        $feeDetails = new Currency(array('value' => $item->price, 'currency' => $this->sitecountry->currency_code));
        $plan = new Plan();
        $plan->setName($item->name)->setDescription(trim($item->summary))->setType('INFINITE');
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
            ->setType('REGULAR')
            ->setFrequency(ucwords(rtrim($item->product->subscription_period_type, "s")))
            ->setFrequencyInterval($item->product->subscription_period_amount)
            ->setCycles("0")
            ->setAmount($feeDetails);

        $merchantPreferences = new MerchantPreferences();
        $this->setUrls('subscription');
        $merchantPreferences->setReturnUrl($this->returnUrl)
            ->setCancelUrl($this->cancelUrl)
            ->setAutoBillAmount("yes")
            ->setInitialFailAmountAction("CONTINUE")
            ->setMaxFailAttempts("0")
            ->setSetupFee($feeDetails);

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        $request = clone $plan;

        try {
            $output = $plan->create($this->_api_context);
        } catch (\Exception $ex) {
            $this->catchException($ex);
        }


        $pp_state = $plan->getState();
        if($pp_state == 'CREATED')
        {

            try
            {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')->setPath('/')->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $plan->update($patchRequest, $this->_api_context);
                $plan = Plan::get($plan->getId(), $this->_api_context);
            } catch (\Exception $ex) {
                $this->catchException($ex);
            }

            $pp_id = $plan->getId();
            $billingPlan = BillingPlan::create([
                'pp_plan_id' => $pp_id,
                'membership_types_id' => $item->product->id,
                'coupons_id' => 0,
                'amount' => $item->price,
                'subscription_period_amount' => $item->product->subscription_period_amount,
                'subscription_period_type' => $item->product->subscription_period_type,
                'membership_update' => $item->product->updated_at->toDateTimeString()
            ]);
            return $billingPlan;
        }

        return false;
    }

    public function purchaseSubscription($savedPlan, $item)
    {
                if(isset($item->product->subscription_period_amount) && isset($item->product->subscription_period_type))
                {
                    if($item->product->subscription_period_type == 'Days') $dt = Carbon::now()->addDays($item->product->subscription_period_amount);
                    if($item->product->subscription_period_type == 'Weeks') $dt = Carbon::now()->addWeeks($item->product->subscription_period_amount);
                    if($item->product->subscription_period_type == 'Months') $dt = Carbon::now()->addMonths($item->product->subscription_period_amount);
                    if($item->product->subscription_period_type == 'Years') $dt = Carbon::now()->addYears($item->product->subscription_period_amount);
                } else {
                    $dt = Carbon::now()->addMinute();
                }

        $agreement = new Agreement();
        $agreement->setName($item->name)->setDescription($item->summary)->setStartDate($dt->toIso8601String());

        $plan = new Plan();
        $plan->setId($savedPlan->pp_plan_id);
        $agreement->setPlan($plan);

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $agreement->setPayer($payer);

        try {
            $agreement = $agreement->create($this->_api_context);
            $approvalUrl = $agreement->getApprovalLink();
        } catch (\Exception $ex) {
            $this->catchException($ex);
        }

        if(isset($approvalUrl)) {
            // add payment ID to session
            \Session::put('paypal_payment_id', $agreement->getId());
            // redirect to paypal
            return \Redirect::away($approvalUrl);
        }

        return \Redirect::route('original.route')
            ->with('error', 'Unknown error occurred');
    }

    public function purchaseItems($summary = '')
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');


        // add item to list
        $item_list = new ItemList();
        $item_list->setItems($this->items);

        $amount = new Amount();
        $amount->setCurrency($this->sitecountry->currency_code)
            ->setTotal($this->total);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($summary);

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($this->returnUrl) // Specify return URL
        ->setCancelUrl($this->cancelUrl);

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\Exception $ex) {
            $this->catchException($ex);
        }
        //dd($payment);

        $approvalUrl = $payment->getApprovalLink();

        if(isset($approvalUrl)) {
            // redirect to paypal
            return \Redirect::away($approvalUrl);
        }

        return \Redirect::route('original.route')
            ->with('error', 'Unknown error occurred');
    }

    public function success($type)
    {
        if($type == 'subscription') return $this->executeSubscription();
        return $this->execute();
    }


    
    private function execute()
    {
        if (isset($_GET['paymentId']))
        {
            $paymentId = $_GET['paymentId'];
            $payment = Payment::get($paymentId, $this->_api_context);

            $execution = new PaymentExecution();
            $execution->setPayerId($_GET['PayerID']);
            try {
                $payment->execute($execution, $this->_api_context);
                try {
                    $payment = Payment::get($paymentId, $this->_api_context);
                } catch (\Exception $ex) {
                    $this->catchException($ex);
                }
            } catch (\Exception $ex) {
                $this->catchException($ex);
            }
            $payment = $this->logSale($payment, 'sale');
            return $payment;
        }
        return false;

    }

    private function catchException($ex)
    {
        $errorMessage = "Exception: " . $ex->getMessage() . PHP_EOL;
        if (\Config::get('app.debug')) {
            echo $errorMessage;
            $this->ppLogger($errorMessage, 'error', 'PayPalError');
            exit;
        } else {
            $this->ppLogger($errorMessage, 'error', 'PayPalError');
            die('An error occurred, sorry for the inconvenience.');
        }
    }



    private function executeSubscription()
    {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $agreement = new Agreement();
            try {
                $agreement->execute($token, $this->_api_context);
            } catch (\Exception $ex) {
                $this->catchException($ex);
            }

            // Make a get call to retrieve the executed agreement details
            try {
                $agreement = \PayPal\Api\Agreement::get($agreement->getId(), $this->_api_context);
            } catch (\Exception $ex) {
                $this->catchException($ex);
            }
            //subscription purchased
            $transaction = $this->logSale($agreement, 'agreement');
            return $transaction;
        } else {
            return false;
        }
    }

    
    private function logSale($sale, $type)
    {
        //$this->ppLogger(serialize($sale));
        $transaction = false;
        $gateway = PaymentGateway::where('slug', 'PaypalRest')->first();
        if($type == 'agreement')
        {
            $transaction = Transactions::create([
                'user_purchase_id' => 0,
                'payment_gateway_id' => $gateway->id,
                'type' => 'agreement',
                'trx_id' => '',
                'agreement_id' => $sale->id,
                'amount' => 0
            ]);
        }
        if($type == 'sale')
        {
            if($sale->state != 'approved') return false;
            $total = 0;
            foreach($sale->transactions as $transaction)
            {
                if(isset($transaction->amount->total)) $total = $total + $transaction->amount->total;
            }

            $transaction =  Transactions::create([
                'user_purchase_id' => 0,
                'payment_gateway_id' => $gateway->id,
                'type' => 'sale',
                'trx_id' => $sale->id,
                'agreement_id' => '',
                'amount' => $total
            ]);
        }
        return $transaction;
    }

    private function setIpnConfig()
    {
        $ppmode = Settings::get('PaypalRest_mode');
        $this->listener->use_sandbox = ($ppmode == 'live') ? false : true;
        $this->listener->use_curl = true;
        $this->listener->follow_location = false;
        $this->listener->timeout = 30;
        $this->listener->verify_ssl = true;
    }

    public function verifyIpn($request = null)
    {
        $this->logIpn('start ipn');
        $this->listener = new IpnListener();
        $this->setIpnConfig();

        if ($verified = $this->listener->processIpn($request))
        {
            $this->ipnPassthrough($request);
            $this->logIpn($this->listener->getTextReport(), true);
            $this->ipnRequest = $request;
            return true;
        } else {
            $this->logIpn('ipn failed', true);
            $this->logIpn($this->listener->getErrors(), true);
            return false;
        }
    }

    public function ipnGet($field)
    {
        return $this->ipnRequest->input($field);
    }

    private function logIpn($content, $append = false)
    {
        $logpath = storage_path().'/logs/PaypalRest/ipn.log';
        if($append){
            \File::append($logpath, $content);
        } else {
            \File::put($logpath, $content);
        }
    }

    private function ipnPassthrough($request)
    {
        $ipnUrls = Settings::get('PaypalRest_ipn_passthrough');
        if($ipnUrls == "") return;

        $req = $this->listener->buildPost($request);
        $ipnUrls = explode(",", $ipnUrls);
        foreach ($ipnUrls as $ipnUrl)
        {
            if($ipnUrl != "") $this->curlPost($ipnUrl, $req);
        }

    }

    protected function curlPost($url, $req)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return;
    }

    public function cancel()
    {
        return;
    }

    public function verifyWebhook($bodyReceived, $request)
    {
        $this->listener = new WebHookListener();
        $result = $this->listener->verify($bodyReceived, $this->_api_context);
        if(!$result)
        {
            switch($request['event_type'])
            {
                case "BILLING.SUBSCRIPTION.CANCELLED" :
                    $this->ppLogger($request);
                    return true;
                    break;
                case "BILLING.SUBSCRIPTION.CREATED" :
                    $this->ppLogger($request);
                    return true;
                    break;
                case "BILLING.SUBSCRIPTION.SUSPENDED" :
                    $this->ppLogger($request);
                    return true;
                    break;
            }
            $error = $this->listener->getError();
            $this->ppLogger($error, 'error');
            return false;
        }
        $this->ppLogger($request);
        return true;
    }

    private function ppLogger($content, $type='info', $logName='WebHook')
    {
        $log = new Logger($logName);
        $log->pushHandler(new RotatingFileHandler(storage_path().'/logs/'.$logName.'.log', 7));
        if($type == 'error') $log->addError($content);
        if($type == 'info') $log->addInfo($content);
    }

    public function cancelSubscription()
    {

    }



}
