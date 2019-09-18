<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : Settings.php
 **/

namespace Quantum\base\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Quantum\base\Http\Requests\Admin\UpdateRegistrationFormRequest;
use Quantum\base\Services\MembershipSettingsService;

class MembershipSettings extends Controller
{

    /**
     * @var MembershipSettingsService
     */
    private $membershipSettingsService;

    public function __construct(MembershipSettingsService $membershipSettingsService)
    {
        $this->membershipSettingsService = $membershipSettingsService;
    }

    public function registration_form()
    {
        return view('base::admin.Membership.Settings.Registration.index');
    }
    
    public function registration_form_update(UpdateRegistrationFormRequest $request)
    {
        $this->membershipSettingsService->update($request);
        return view('base::admin.Membership.Settings.Registration.index');
    }

}