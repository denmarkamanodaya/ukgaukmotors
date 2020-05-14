<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : PaymentService.php
 **/

namespace Quantum\base\Services;

use Illuminate\Support\Facades\Auth;
use Quantum\base\Models\User;
use Quantum\base\Events\PaymentReceived;
use Quantum\base\Events\PaymentRefunded;
use Quantum\base\Models\IpnLog;
use Quantum\base\Models\PaymentGateway;
use Quantum\base\Models\Transactions;
use Quantum\base\Models\UserPurchase;
use Quantum\base\Models\UserPurchaseItems;
use Quantum\base\Models\WebhookLog;
use Quantum\base\Events\MembershipPurchased;
use Quantum\base\Models\MembershipTypes;
use Quantum\base\Models\UserMembership;
use Quantum\base\Services\MembershipService;
use Session;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class PaymentService
{
    protected $payment;
    protected $subscription = false;
    protected $item;
    protected $summary;
    protected $sitecountry;
    /**
     * @var MembershipService
     */
    private $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->setLocaleInfo();
        $this->membershipService = $membershipService;
    }

    private function setGateway($gateway)
    {
        $gateway = PaymentGateway::where('slug', $gateway)->firstOrFail();
        $this->payment = \Payment::setGateway($gateway);
    }

    //prepare cart items
    public function paymentPrepare($gateway)
    {
        if(\Cart::count() < 1)
        {
            $members = Auth::check() ? 'members' : '';
            return redirect($members.'/');
        }
        
        $this->setGateway($gateway);
        
        $plan = $this->setItems();
        if($this->subscription)
        {
            $this->subscription = false; //reset 
            return $this->payment->purchaseSubscription($plan, $this->item);
        }
        return $this->payment->purchaseItems($this->summary);

    }

    private function setItems()
    {
        $items = \Cart::getItems();
        foreach ($items as $item)
        {
            $this->summary = $item->summary;
            
            if(isset($item->subscription) && $item->subscription == true)
            {
                $this->subscription = true;
                $this->item = $item;
                return $this->payment->addSubscription($item); //return plan
            }
            $this->payment->addItem($item->name, $item->price, $item->quantity);
        }
        return false;
    }

    public function success($type, $gateway)
    {
        $this->setGateway($gateway);
        $transaction = $this->payment->success($type);
        if($transaction) $this->logSale($transaction);
        return $this->postSale($transaction);
    }

    public function cancel($type, $gateway)
    {
        $this->setGateway($gateway);
        $this->payment->cancel($type);
        if(Auth::check()){
            $url = url(\Settings::get('members_home_page'));
        } else {
            $url = url();
        }
        return redirect($url);
    }

    private function postSale($transaction)
    {
        $url = '';
        if(!$transaction)
        {
            flash('There was a problem with the payment.')->error();
            if(Auth::check()){
                $url = url(\Settings::get('members_home_page'));
            } else {
                $url = url();
            }
        } else {
            $items = \Cart::getItems();
            foreach ($items as $item)
            {

                if(Auth::check()){
                    if($item->type == 'membership') \Event::fire(new MembershipPurchased($transaction));
                    if (Session::has('membership_alt_url')) {
                        Session::forget('membership_alt_url');
                        $url = url($item->product->page_after_registration);
                    } else {
                        $url = url($item->product->members_page_after_payment);
                    }
                } else {
                    $slug = str_slug($item->product->name);
                    $url = url($item->product->guest_page_after_payment.'/'.$slug);
                }
            }
        }
        \Cart::clear();
        flash('Payment has been received.')->success();
        return redirect($url);
    }

    private function logSale($transaction)
    {
        $total = 0;
        $subtotal = 0;


        $userId = Auth::check() ? Auth::user()->id : 0;

        $userPurchase = UserPurchase::create([
        'user_id' => $userId,
        'guest_key' => '',
        'subtotal' => 0,
        'total' => 0,
        'expires' => 0,
        'subscription' => 0,
        'subscription_period_amount' => '',
        'subscription_period_type',
        'status' => 'active'
        ]);



        $transaction->user_purchase_id = $userPurchase->id;
        $transaction->user_id = $userId;
        $transaction->save();

        $items = \Cart::getItems();
        $purchasedItems = [];
        foreach ($items as $item)
        {
            $subtotal = $subtotal + $item->price;
            $userPurchase->subscription = $item->subscription;
            $userPurchase->expires = $item->expires;
            $userPurchase->subscription_period_amount = $item->product->subscription_period_amount;
            $userPurchase->subscription_period_type = $item->product->subscription_period_type;

            $purchase_item = UserPurchaseItems::create([
            'user_purchase_id' => $userPurchase->id,
            'item_id' => 0,
            'name' => $item->name,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'model_id' => $item->model_id,
            'model' => $item->model
            ]);
            array_push($purchasedItems, $item->name);

        }
        $userPurchase->subtotal = $subtotal;
        $userPurchase->total = $subtotal;
        $userPurchase->save();
        
        $userPurchase->purchasedItems = $purchasedItems;

        if($transaction->type == 'sale') \Event::fire(new PaymentReceived($transaction, $userPurchase));

        session(['user_purchase_id' => $userPurchase->id]);
        session(['purchase_total' => $subtotal]);

    }

    private function setLocaleInfo()
    {
        $this->sitecountry = \Countries::siteCountry();
    }
    
    public function ipn($request, $gateway)
    {
        $this->setGateway($gateway);
        $transaction = $this->payment->verifyIpn($request);
        if($transaction)
        {
            $this->logIpn($request);
            $this->processIpn($request);
        }
        return $transaction;
    }

    private function logIpn($request)
    {
        if (isset($request->txn_id)) {
            $order = IpnLog::firstOrNew(array('txn_id' => $request->txn_id));
            $order->fill($request->all());
            $order->full_ipn = json_encode($request->all());
            $order->save();
        }

    }

    private function processIpn($request)
    {
        // 1. Make sure the payment status is "Completed"
        if ($_POST['payment_status'] != 'Completed') {
            // simply ignore any IPN that is not completed
            exit(0);
        }
        $transaction = false;
        if ($_POST['txn_type'] == 'recurring_payment') {
            $transaction = $this->processSubscription($request);
        }
        //log sale
        if($transaction)
        {
            $this->logTransaction($transaction, $request);
        }
    }

    private function processSubscription($request, $webhook=false)
    {
        $billingId = ($webhook) ? $request['resource']['billing_agreement_id'] : $request->recurring_payment_id;
        $transaction = Transactions::with('user_purchase')->where('agreement_id', $billingId)->first();
        if($transaction)
        {
            if($this->validTransaction($transaction, $request, $webhook))
            {
                if($this->alreadyProcessed($request, $webhook)) return false;
                if(!$purchasedItems = $this->processItems($transaction, $request)) return false;
                $transaction->user_purchase->purchasedItems = $purchasedItems;
            }
        }
        return $transaction;
    }

    private function alreadyProcessed($request, $webhook=false)
    {
        if($webhook)
        {
            $payId = isset($request['resource']['parent_payment']) ? $request['resource']['parent_payment'] : $request['resource']['id'];
            return Transactions::where('trx_id', $payId)->first();
        }
        return Transactions::where('trx_id', $request->txn_id)->first();
    }

    private function validTransaction($transaction, $request, $webhook=false)
    {
        if($webhook)
        {
            if($this->sitecountry->currency_code != $request['resource']['amount']['currency']) return false;
            if($transaction->user_purchase->total != $request['resource']['amount']['total']) return false;
            return true;
        }
        if($this->sitecountry->currency_code != $request->mc_currency) return false;
        if($transaction->user_purchase->total != $request->mc_gross) return false;
        return true;
    }

    private function processItems($transaction, $request)
    {
        $purchasedItems = [];
        //only process subscription items
        if($transaction->user_purchase->subscription != '1') return false;
        foreach ($transaction->user_purchase->items as $item)
        {
            array_push($purchasedItems, $item->name);
            if($item->model == '\Quantum\base\Models\MembershipTypes') $this->updateMembership($transaction, $item);
        }
        return $purchasedItems;
    }

    private function updateMembership($transaction, $item)
    {
        $userMembership = UserMembership::where('user_id', $transaction->user_purchase->user_id)
            ->where('membership_types_id' , $item->model_id)
            ->where('user_purchase_id' , $transaction->user_purchase->id)
            ->where('expires', '1')->first();
        if($userMembership)
        {
            if(!$user = User::where('id', $transaction->user_purchase->user_id)->first()) return;

            if(!$membership = MembershipTypes::with('roles')->where('id', $item->model_id)->tenant()->first()) return;
            
            foreach($membership->roles as $role)
            {
                if(!$user->hasRole($role))
                {
                    $user->roles()->attach($role);
                }
            }

            $expireDate = $this->membershipService->createExpireDate($transaction->user_purchase);
            $userMembership->expires_on = $expireDate;
            $userMembership->status = 'active';
            $userMembership->save();
        }
    }

    private function logTransaction($transaction, $request)
    {

            $trans =  Transactions::create([
                'user_purchase_id' => $transaction->user_purchase_id,
                'payment_gateway_id' => $transaction->payment_gateway_id,
                'type' => 'sale',
                'trx_id' => $request->txn_id,
                'agreement_id' => $transaction->agreement_id,
                'amount' => $request->mc_gross,
                'user_id' => $transaction->user_purchase->user_id
            ]);
        $userPurchase = $transaction->user_purchase;
        
        \Event::fire(new PaymentReceived($transaction, $userPurchase));

        return $trans;
    }

    public function webhook($request, $gateway)
    {
        $this->setGateway($gateway);
        //validate input
        $bodyReceived = file_get_contents('php://input');
	#error_log(date('Y-m-d H:i:s') . " | " . print_r(array($bodyReceived), true) . "\n", 3, '/tmp/dk_server_log');
        //dd($bodyReceived);
        $transaction = $this->payment->verifyWebHook($bodyReceived, $request);
        //already processed hook
        $webhook = WebhookLog::where('webhookid', $request['id'])->first();
        if($webhook) return $transaction;
        //new valid hook
        if($transaction)
        {
            $this->logWebhook($request);
            $this->processWebhook($request);
        }
        return $transaction;
    }

    private function logWebhook($request)
    {
        if (isset($request['id'])) {
            $order = WebhookLog::firstOrNew(array('webhookid' => $request['id']));
            $order->webhookid = $request['id'];
            $order->event_version = $request['event_version'];
            $order->resource_type = $request['resource_type'];
            $order->event_type = $request['event_type'];
            $order->summary = isset($request['summary']) ? $request['summary'] : null;
            $order->resource_id = isset($request['resource']['id']) ? $request['resource']['id'] : null;
            $order->resource_state = isset($request['resource']['state']) ? $request['resource']['state'] : null;
            $order->resource_amount_total = isset($request['resource']['amount']['total']) ? $request['resource']['amount']['total'] : null;
            $order->resource_amount_currency = isset($request['resource']['amount']['currency']) ? $request['resource']['amount']['currency'] : null;
            $order->resource_payment_mode = isset($request['resource']['payment_mode']) ? $request['resource']['payment_mode'] : null;
            $order->resource_protection_eligibility = isset($request['resource']['protection_eligibility']) ? $request['resource']['protection_eligibility'] : null;
            $order->resource_protection_eligibility_type = isset($request['resource']['protection_eligibility_type']) ? $request['resource']['protection_eligibility_type'] : null;
            $order->resource_transaction_fee_value = isset($request['resource']['transaction_fee']['value']) ? $request['resource']['transaction_fee']['value'] : null;
            $order->resource_transaction_fee_currency = isset($request['resource']['transaction_fee']['currency']) ? $request['resource']['transaction_fee']['currency'] : null;
            $order->resource_invoice_number = isset($request['resource']['invoice_number']) ? $request['resource']['invoice_number'] : null;
            $order->resource_custom = isset($request['resource']['custom']) ? $request['resource']['custom'] : null;
            $order->resource_parent_payment = isset($request['resource']['parent_payment']) ? $request['resource']['parent_payment'] : null;
            $order->resource_billing_agreement_id = isset($request['resource']['billing_agreement_id']) ? $request['resource']['billing_agreement_id'] : null;
            $order->full_json = json_encode($request->all());
            $order->save();
        }
    }

    private function processWebhook($request)
    {
        switch($request['event_type'])
        {
            case "PAYMENT.SALE.COMPLETED" :
                return $this->hook_sale_complete($request);
                break;
            case "BILLING.SUBSCRIPTION.CREATED" :
                return $this->hook_subscription_created($request);
                break;
            case "BILLING.SUBSCRIPTION.CANCELLED" :
                return $this->hook_subscription_cancelled($request);
                break;
            case "BILLING.SUBSCRIPTION.SUSPENDED" :
                return $this->hook_subscription_cancelled($request);
                break;
            case "PAYMENT.SALE.DENIED" :
                return $this->hook_payment_cancelled($request);
                break;
            case "PAYMENT.SALE.REFUNDED" :
                return $this->hook_payment_refunded($request);
                break;
            case "PAYMENT.SALE.REVERSED" :
                return $this->hook_payment_refunded($request);
                break;
        }
    }

    private function hook_sale_complete($request)
    {

        if (isset($request['resource']['billing_agreement_id']) && $request['resource']['billing_agreement_id'] != '') {
            $transaction = $this->processSubscriptionHook($request);
            if(!$transaction) return false;
            $this->logTransactionHook($transaction, $request);
        } else {
            //todo non sub sale.
            return true;
        }
    }

    private function hook_subscription_created($request)
    {
        //$this->payLogger('Subscription Hook');
        //$this->payLogger(serialize($request['resource']));
        //$this->payLogger(serialize('ID :'.$request['resource']['id']));
        //$this->payLogger(serialize('State :'.$request['resource']['state']));
        if($request['resource']['state'] == 'Active')
        {
            /*$transaction = Transactions::with('user_purchase')->where('type', 'agreement')->where('agreement_id', $request['resource']['id'])->first();

            if($transaction)
            {
                Transactions::create([
                    'user_purchase_id' => $transaction->user_purchase_id,
                    'payment_gateway_id' => $transaction->payment_gateway_id,
                    'type' => 'sale',
                    'trx_id' => 'Initial Payment',
                    'agreement_id' => $transaction->agreement_id,
                    'amount' => $request['resource']['plan']['merchant_preferences']['setup_fee']['value'],
                    'user_id' => $transaction->user_purchase->user_id
                ]);
            }*/
        }
        return true;
    }

    private function processSubscriptionHook($request)
    {
        $transaction = Transactions::with('user_purchase')->where('type', 'agreement')->where('agreement_id', $request['resource']['billing_agreement_id'])->first();
        if($transaction)
        {

            if(!$this->validTransaction($transaction, $request, true)) return false;
            if($this->alreadyProcessed($request, true)) return false;
            if(!$purchasedItems = $this->processItems($transaction, $request)) return false;

            if($transaction->status == 0)
            {
                $transaction->status = 1;
                $transaction->save();
            }

            $transaction->user_purchase->purchasedItems = $purchasedItems;
        }
        return $transaction;
    }

    private function logTransactionHook($transaction, $request)
    {
        $payId = isset($request['resource']['parent_payment']) ? $request['resource']['parent_payment'] : $request['resource']['id'];
        $trans =  Transactions::create([
            'user_purchase_id' => $transaction->user_purchase_id,
            'payment_gateway_id' => $transaction->payment_gateway_id,
            'type' => 'sale',
            'trx_id' => $payId,
            'agreement_id' => $transaction->agreement_id,
            'amount' => $request['resource']['amount']['total'],
            'user_id' => $transaction->user_purchase->user_id
        ]);
        $userPurchase = $transaction->user_purchase;

        \Event::fire(new PaymentReceived($transaction, $userPurchase));

        return $trans;
    }

    private function hook_subscription_cancelled($request)
    {
        if (isset($request['resource']['id']) && $request['resource']['id'] != '') {
            $transaction = Transactions::with('user_purchase')->where('type', 'agreement')->where('agreement_id', $request['resource']['id'])->first();
            if(!$transaction) return false;
            $transaction->status = 0;
            $transaction->save();
            //$this->logTransactionHook($transaction, $request);
        }
    }

    private function hook_payment_cancelled($request)
    {
        if (isset($request['resource']['billing_agreement_id']) && $request['resource']['billing_agreement_id'] != '') {
            $transaction = Transactions::with('user_purchase')->where('type', 'agreement')->where('agreement_id', $request['resource']['billing_agreement_id'])->first();
            if(!$transaction) return false;
            $transaction->status = 0;
            $transaction->save();
            //$this->logTransactionHook($transaction, $request);
        }
    }

    private function hook_payment_refunded($request)
    {
        if (isset($request['resource']['billing_agreement_id']) && $request['resource']['billing_agreement_id'] != '') {
            $transaction = Transactions::with('user_purchase')->where('type', 'agreement')->where('agreement_id', $request['resource']['billing_agreement_id'])->first();
            if(!$transaction) return false;
            $transaction->status = 0;
            $transaction->save();
            //$this->logTransactionHook($transaction, $request);
            if($payment = Transactions::where('trx_id', $request['resource']['parent_payment'])->first()){
                $payment->status = 0;
                $payment->save();
            }
            $userPurchase = $transaction->user_purchase;

            $user = User::where('id', $transaction->user_id)->first();
            if($purchaseItems = UserPurchaseItems::where('user_purchase_id', $transaction->user_purchase_id)
                ->where('model', '\Quantum\base\Models\MembershipTypes')->get())
            {
                foreach ($purchaseItems as $purchaseItem)
                {
                    $this->membershipService->removeUserMembersip($purchaseItem->model_id, $user, true);
                }
            }


        }
    }

    private function payLogger($content, $type='info', $logName='PaymentDebug')
    {
        $log = new Logger($logName);
        $log->pushHandler(new RotatingFileHandler(storage_path().'/logs/'.$logName.'.log', 7));
        if($type == 'error') $log->addError($content);
        if($type == 'info') $log->addInfo($content);
    }

    
}
