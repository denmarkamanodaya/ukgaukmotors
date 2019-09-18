<?php

namespace Quantum\base\Http\Controllers\Admin;

use Quantum\base\Services\RecaptchaService;
use Quantum\base\Http\Requests\RecaptchaSettingsRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Recaptcha extends Controller
{


    /**
     * @var RecaptchaService
     */
    private $recaptchaService;

    public function __construct(RecaptchaService $recaptchaService)
    {

        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('base::admin.Recaptcha.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RecaptchaSettingsRequest $request)
    {
        $this->recaptchaService->updateSettings($request);
        return redirect('/admin/recaptcha/settings');
    }
}
