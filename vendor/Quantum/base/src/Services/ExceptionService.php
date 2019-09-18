<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: dave
 *
 * File : ExceptionService.php
 **/

namespace Quantum\base\Services;

use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Quantum\base\Jobs\ExceptionNotify;
use Zttp\Zttp;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Exception;
use GuzzleHttp\Middleware;

class ExceptionService
{
    /**
     * The Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    protected $context;

    protected $exception = [];

    protected $http;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function log(Exception $exception)
    {
        if(!config('exception.exception_log')) return;
        if(!config('exception.exception_project_key')) return;
        $e = FlattenException::create($exception);
        $this->exception['message'] = $e->toArray();
        $this->exception['project'] = config('exception.exception_project_key');
        $this->addContext();
        $this->notify($this->exception);
    }

    protected function addContext(array $context = [])
    {
        // Add session data.
        if ($session = $this->app->session->all()) {
            if (empty($this->exception['person']) or ! is_array($this->exception['person'])) {
                $this->exception['person'] = [];
            }
            // Merge person context.
            if (isset($context['person']) and is_array($context['person'])) {
                $this->exception['person'] = $context['person'];
                unset($context['person']);
            }


            // Add user session information.
            if (isset($this->exception['person']['session'])) {
                $this->exception['person']['session'] = array_merge($session, $this->exception['person']['session']);
            } else {
                $this->exception['person']['session'] = $session;
            }
            // User session id as user id if not set.
            if (! isset($this->exception['person']['id'])) {
                $this->exception['person']['id'] = $this->app->session->getId();
            }

            if(\Auth::check())
            {
                $user = \Auth::user();
                $this->exception['person']['user']['id'] = $user->id;
                $this->exception['person']['user']['username'] = $user->username;
                $this->exception['person']['user']['email'] = $user->email;
            }
        }
    }


    public function notify($exception)
    {
        if(!isset($exception['message'])) return;
        $accessToken = $this->getAccess();
        if($accessToken) $this->sendException($accessToken, $exception);
    }

    private function getAccess()
    {
        $api_id = config('exception.exception_api_id');
        $api_secret = config('exception.exception_api_secret');
        if(!$api_id || !$api_secret) return false;
        $this->http = new Client(['base_uri' => 'http://quantumidea2.dev']);

        $body = [
            'client_id'     => $api_id,
            'client_secret' => $api_secret,
            'grant_type'    => 'client_credentials',
            'scope'         => '*'
        ];

        $response = $this->http->request('POST', '/oauth/token', ['json' => $body]);
        $response_code = $response->getStatusCode();
        if($response_code != 200) return false;
        $responseBody = $response->getBody(true);
        $responseArr = json_decode($responseBody, true);
        return isset($responseArr['access_token']) ? $responseArr['access_token'] : false;
    }

    private function sendException($accessToken, $exception)
    {
        // Grab the client's handler instance.
        $clientHandler = $this->http->getConfig('handler');
        // Create a middleware that echoes parts of the request.
        $tapMiddleware = Middleware::tap(function ($request) {
            echo $request->getHeaderLine('Content-Type');
            // application/json
            echo $request->getBody();
            // {"foo":"bar"}
        });

        $response = $this->http->request('POST', '/api/bug', [
            'handler' => $tapMiddleware($clientHandler),
            'headers'  => [
                'Authorization' => 'Bearer '.$accessToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'form_params' => ['project' => '1'],
            'debug' => true
        ]);
        $data = $response->getBody()->getContents();
        dd($data);
        $response_code = $response->getStatusCode();
        dd($response_code);
        $responseBody = $response->getBody(true);
        $responseArr = json_decode($responseBody, true);
        dd($responseArr);
    }
}