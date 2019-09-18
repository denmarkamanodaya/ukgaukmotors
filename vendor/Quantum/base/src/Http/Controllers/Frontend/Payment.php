<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Cart.php
 **/

namespace Quantum\base\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Quantum\base\Services\PaymentService;

class Payment extends Controller
{

    /**
     * @var PaymentService
     */
    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        //$this->middleware('guest', ['except' => 'getLogout']);
        $this->middleware('firewall');
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
    
    public function ipn(Request $request, $gateway)
    {
        $this->paymentService->ipn($request, $gateway);
        
    }

    public function webhook(Request $request, $gateway)
    {
        $this->paymentService->webhook($request, $gateway);
    }
    public function webhookTest($gateway, $type=null)
    {
        $id1= 'WH-9UG43882HX7271132-6E0871324L79496167';
        $id2= 'I-SJW3B3BRPXMG';
        $id3= 'PAY-7F371669SL612941HK7RQFO';

        switch($type)
        {
            case "subcancel" :
                $requestBody = json_decode('{"id":"'.$id1.'","event_version":"1.0","create_time":"2016-09-21T22:00:45Z","resource_type":"Agreement","event_type":"BILLING.SUBSCRIPTION.CANCELLED","summary":"Payment completed for $ 21.0 USD","resource":{"id":"'.$id2.'","billing_agreement_id":"'.$id2.'","state":"completed","amount":{"total":"2.99","currency":"GBP","details":{"subtotal":"17.50","tax":"1.30","shipping":"2.20"}},"payment_mode":"INSTANT_TRANSFER","protection_eligibility":"ELIGIBLE","protection_eligibility_type":"ITEM_NOT_RECEIVED_ELIGIBLE,UNAUTHORIZED_PAYMENT_ELIGIBLE","transaction_fee":{"value":"0.91","currency":"USD"},"invoice_number":"57e3028db8d1b","custom":"","parent_payment":"'.$id3.'","create_time":"2016-09-21T21:59:02Z","update_time":"2016-09-21T22:00:06Z","links":[{"href":"https://api.sandbox.paypal.com/v1/payments/sale/80F85758S3080410K","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/payments/sale/80F85758S3080410K/refund","rel":"refund","method":"POST"},{"href":"https://api.sandbox.paypal.com/v1/payments/payment/PAY-7F371669SL612941HK7RQFDQ","rel":"parent_payment","method":"GET"}]},"links":[{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9UG43882HX7271132-6E0871324L7949614","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9UG43882HX7271132-6E0871324L7949614/resend","rel":"resend","method":"POST"}]}', true);
                break;
            case "subsuspend" :
                $requestBody = json_decode('{"id":"'.$id1.'","event_version":"1.0","create_time":"2016-09-21T22:00:45Z","resource_type":"Agreement","event_type":"BILLING.SUBSCRIPTION.CANCELLED","summary":"Payment completed for $ 21.0 USD","resource":{"id":"'.$id2.'","billing_agreement_id":"'.$id2.'","state":"completed","amount":{"total":"2.99","currency":"GBP","details":{"subtotal":"17.50","tax":"1.30","shipping":"2.20"}},"payment_mode":"INSTANT_TRANSFER","protection_eligibility":"ELIGIBLE","protection_eligibility_type":"ITEM_NOT_RECEIVED_ELIGIBLE,UNAUTHORIZED_PAYMENT_ELIGIBLE","transaction_fee":{"value":"0.91","currency":"USD"},"invoice_number":"57e3028db8d1b","custom":"","parent_payment":"'.$id3.'","create_time":"2016-09-21T21:59:02Z","update_time":"2016-09-21T22:00:06Z","links":[{"href":"https://api.sandbox.paypal.com/v1/payments/sale/80F85758S3080410K","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/payments/sale/80F85758S3080410K/refund","rel":"refund","method":"POST"},{"href":"https://api.sandbox.paypal.com/v1/payments/payment/PAY-7F371669SL612941HK7RQFDQ","rel":"parent_payment","method":"GET"}]},"links":[{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9UG43882HX7271132-6E0871324L7949614","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9UG43882HX7271132-6E0871324L7949614/resend","rel":"resend","method":"POST"}]}', true);
                break;
            case "refunded" :
                $requestBody = json_decode('{"id":"'.$id1.'","event_version":"1.0","create_time":"2016-09-21T22:00:45Z","resource_type":"Agreement","event_type":"PAYMENT.SALE.REFUNDED","summary":"Payment completed for $ 21.0 USD","resource":{"id":"80F85758S3080410K","billing_agreement_id":"'.$id2.'","state":"completed","amount":{"total":"2.99","currency":"GBP","details":{"subtotal":"17.50","tax":"1.30","shipping":"2.20"}},"payment_mode":"INSTANT_TRANSFER","protection_eligibility":"ELIGIBLE","protection_eligibility_type":"ITEM_NOT_RECEIVED_ELIGIBLE,UNAUTHORIZED_PAYMENT_ELIGIBLE","transaction_fee":{"value":"0.91","currency":"USD"},"invoice_number":"57e3028db8d1b","custom":"","parent_payment":"'.$id3.'","create_time":"2016-09-21T21:59:02Z","update_time":"2016-09-21T22:00:06Z","links":[{"href":"https://api.sandbox.paypal.com/v1/payments/sale/80F85758S3080410K","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/payments/sale/80F85758S3080410K/refund","rel":"refund","method":"POST"},{"href":"https://api.sandbox.paypal.com/v1/payments/payment/PAY-7F371669SL612941HK7RQFDQ","rel":"parent_payment","method":"GET"}]},"links":[{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9UG43882HX7271132-6E0871324L7949614","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9UG43882HX7271132-6E0871324L7949614/resend","rel":"resend","method":"POST"}]}', true);
                break;
            default :
                $requestBody = json_decode('{"id":"'.$id1.'","event_version":"1.0","create_time":"2016-09-21T22:00:45Z","resource_type":"sale","event_type":"PAYMENT.SALE.COMPLETED","summary":"Payment completed for $ 21.0 USD","resource":{"id":"80F85758S3080410K","billing_agreement_id":"'.$id2.'","state":"completed","amount":{"total":"2.99","currency":"GBP","details":{"subtotal":"17.50","tax":"1.30","shipping":"2.20"}},"payment_mode":"INSTANT_TRANSFER","protection_eligibility":"ELIGIBLE","protection_eligibility_type":"ITEM_NOT_RECEIVED_ELIGIBLE,UNAUTHORIZED_PAYMENT_ELIGIBLE","transaction_fee":{"value":"0.91","currency":"USD"},"invoice_number":"57e3028db8d1b","custom":"","parent_payment":"'.$id3.'","create_time":"2016-09-21T21:59:02Z","update_time":"2016-09-21T22:00:06Z","links":[{"href":"https://api.sandbox.paypal.com/v1/payments/sale/80F85758S3080410K","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/payments/sale/80F85758S3080410K/refund","rel":"refund","method":"POST"},{"href":"https://api.sandbox.paypal.com/v1/payments/payment/PAY-7F371669SL612941HK7RQFDQ","rel":"parent_payment","method":"GET"}]},"links":[{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9UG43882HX7271132-6E0871324L7949614","rel":"self","method":"GET"},{"href":"https://api.sandbox.paypal.com/v1/notifications/webhooks-events/WH-9UG43882HX7271132-6E0871324L7949614/resend","rel":"resend","method":"POST"}]}', true);
        }

        $request = collect($requestBody);
        $this->paymentService->webhook($request, $gateway);
    }
}