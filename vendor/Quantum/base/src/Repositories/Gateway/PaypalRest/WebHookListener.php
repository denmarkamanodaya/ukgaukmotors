<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : WebHookListener.php
 **/

namespace Quantum\base\Repositories\Gateway\PaypalRest;

use PayPal\Api\VerifyWebhookSignature;
use PayPal\Api\WebhookEvent;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Settings;

class WebHookListener
{

    protected $error;

    public function verify($requestBody, $apiContext)
    {
        $headers = getallheaders();
        $headers = array_change_key_case($headers, CASE_UPPER);
        $webhookId = Settings::get('PaypalRest_Hook_Id');
        if($webhookId == '')
        {
            $this->error = 'Invalid Webhook ID';
            return false;
        }
        $signatureVerification = new VerifyWebhookSignature();
        $signatureVerification->setAuthAlgo($headers['PAYPAL-AUTH-ALGO']);
        $signatureVerification->setTransmissionId($headers['PAYPAL-TRANSMISSION-ID']);
        $signatureVerification->setCertUrl($headers['PAYPAL-CERT-URL']);
        $signatureVerification->setWebhookId($webhookId); // Note that the Webhook ID must be a currently valid Webhook that you created with your client ID/secret.
        $signatureVerification->setTransmissionSig($headers['PAYPAL-TRANSMISSION-SIG']);
        $signatureVerification->setTransmissionTime($headers['PAYPAL-TRANSMISSION-TIME']);

        $webhookEvent = new WebhookEvent();
        $webhookEvent->fromJson($requestBody);
        $signatureVerification->setWebhookEvent($webhookEvent);
        $request = clone $signatureVerification;

        try {
            /** @var \PayPal\Api\VerifyWebhookSignatureResponse $output */
            $output = $signatureVerification->post($apiContext);
        } catch (\Exception $ex) {
            $exError = $ex->getMessage();
            $this->logWebhook('Webhook Exception :'.$exError, 'info');
            $this->error = $exError;
            return false;
        }
        $result = $output->getVerificationStatus();
        $this->logWebhook('Webhook Result :'.$result, 'info');
        if($result == 'SUCCESS') return true;
        $this->error = 'Invalid WebHook : '.$this->error.' : '.$request->toJSON();
        return false;
    }

    public function getError()
    {
        return $this->error;
    }

    private function logWebhook($content, $type='info')
    {
        $logpath = storage_path().'/logs/PaypalRest';
        if (!is_dir($logpath)) {
            mkdir($logpath);
        }
        $log = new Logger('WebHook');
        $log->pushHandler(new RotatingFileHandler(storage_path().'/logs/WebHook.log', 7));
        if($type == 'error') $log->addError($content);
        if($type == 'info') $log->addInfo($content);
    }


}
